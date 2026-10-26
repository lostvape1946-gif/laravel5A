import os, textwrap
NAMA = "NAMA_ANDA"
NPM  = "NPM_ANDA"
ROOT = "/home/claude/perpustakaan"

def w(path, content):
    p = os.path.join(ROOT, path)
    os.makedirs(os.path.dirname(p), exist_ok=True)
    open(p, "w").write(content)

# name, label, type, rel(model, method, display)
E = [
 dict(m="Kategori", t="kategoris", r="kategori", label="Kategori", fields=[
   ("nama","Nama Kategori","text",None)],
   has=[("bukus","Buku","kategori_id")], belongs=[]),
 dict(m="Buku", t="bukus", r="buku", label="Buku", fields=[
   ("kategori_id","Kategori","select",("Kategori","kategori","nama","kategoris")),
   ("judul","Judul","text",None),("penulis","Penulis","text",None),("tahun","Tahun","number",None)],
   has=[("peminjamans","Peminjaman","buku_id")], belongs=[("kategori","Kategori","kategori_id")]),
 dict(m="Anggota", t="anggotas", r="anggota", label="Anggota", fields=[
   ("nama","Nama","text",None),("npm","NPM","text",None),("email","Email","email",None)],
   has=[("peminjamans","Peminjaman","anggota_id")], belongs=[]),
 dict(m="Peminjaman", t="peminjamans", r="peminjaman", label="Peminjaman", fields=[
   ("anggota_id","Anggota","select",("Anggota","anggota","nama","anggotas")),
   ("buku_id","Buku","select",("Buku","buku","judul","bukus")),
   ("tanggal_pinjam","Tgl Pinjam","date",None),
   ("tanggal_kembali","Tgl Kembali","date_null",None)],
   has=[], belongs=[("anggota","Anggota","anggota_id"),("buku","Buku","buku_id")]),
]

# migrations
for i,e in enumerate(E):
    cols=[]
    for n,l,t,rel in e["fields"]:
        if t=="select": cols.append(f"            $table->foreignId('{n}')->constrained('{rel[3]}')->cascadeOnDelete();")
        elif t=="number": cols.append(f"            $table->unsignedSmallInteger('{n}');")
        elif t=="date": cols.append(f"            $table->date('{n}');")
        elif t=="date_null": cols.append(f"            $table->date('{n}')->nullable();")
        else: cols.append(f"            $table->string('{n}');")
    w(f"database/migrations/2026_10_26_00000{i+1}_create_{e['t']}_table.php", f"""<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{{
    public function up(): void
    {{
        Schema::create('{e['t']}', function (Blueprint $table) {{
            $table->id();
{chr(10).join(cols)}
            $table->timestamps();
        }});
    }}

    public function down(): void
    {{
        Schema::dropIfExists('{e['t']}');
    }}
}};
""")

# models
for e in E:
    fill=", ".join(f"'{f[0]}'" for f in e["fields"])
    rels=""
    for meth,model,fk in e["belongs"]:
        rels+=f"\n    public function {meth}()\n    {{\n        return $this->belongsTo({model}::class, '{fk}');\n    }}\n"
    for meth,model,fk in e["has"]:
        rels+=f"\n    public function {meth}()\n    {{\n        return $this->hasMany({model}::class, '{fk}');\n    }}\n"
    w(f"app/Models/{e['m']}.php", f"""<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Model;

class {e['m']} extends Model
{{
    protected $table = '{e['t']}';
    protected $fillable = [{fill}];
{rels}}}
""")

# controllers
for e in E:
    M=e["m"]; r=e["r"]
    rules=[]; opts=[]; withs=[]
    for n,l,t,rel in e["fields"]:
        if t=="text": rules.append(f"'{n}' => 'required|string|max:255'")
        elif t=="email": rules.append(f"'{n}' => 'required|email|max:255'")
        elif t=="number": rules.append(f"'{n}' => 'required|integer|min:1900|max:2100'")
        elif t=="date": rules.append(f"'{n}' => 'required|date'")
        elif t=="date_null": rules.append(f"'{n}' => 'nullable|date'")
        else:
            rules.append(f"'{n}' => 'required|exists:{rel[3]},id'")
            opts.append(f"'{rel[1]}_list' => {rel[0]}::all()")
            withs.append(f"'{rel[1]}'")
    uses="".join(f"use App\\Models\\{rel[0]};\n" for _,_,t,rel in e["fields"] if t=="select")
    wi=f"with([{', '.join(withs)}])->" if withs else ""
    o=", ".join(opts)
    w(f"app/Http/Controllers/{M}Controller.php", f"""<?php

namespace App\\Http\\Controllers;

use App\\Models\\{M};
{uses}use Illuminate\\Http\\Request;

class {M}Controller extends Controller
{{
    private function rules(): array
    {{
        return [{', '.join(rules)}];
    }}

    public function index()
    {{
        $items = {M}::{wi}latest()->get();
        return view('{r}.index', compact('items'));
    }}

    public function create()
    {{
        return view('{r}.create', [{o}]);
    }}

    public function store(Request $request)
    {{
        {M}::create($request->validate($this->rules()));
        return redirect()->route('{r}.index')->with('success', 'Data berhasil ditambahkan.');
    }}

    public function edit({M} ${r})
    {{
        return view('{r}.edit', ['item' => ${r}{', '+o if o else ''}]);
    }}

    public function update(Request $request, {M} ${r})
    {{
        ${r}->update($request->validate($this->rules()));
        return redirect()->route('{r}.index')->with('success', 'Data berhasil diubah.');
    }}

    public function destroy({M} ${r})
    {{
        ${r}->delete();
        return redirect()->route('{r}.index')->with('success', 'Data berhasil dihapus.');
    }}
}}
""")

# views
nav="".join(f'<li class="nav-item"><a class="nav-link" href="{{{{ route(\'{e["r"]}.index\') }}}}">{e["label"]}</a></li>' for e in E)
w("resources/views/layouts/app.blade.php", f"""<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan - {NAMA} ({NPM})</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">Perpustakaan</span>
        <ul class="navbar-nav me-auto">{nav}</ul>
        <span class="navbar-text">{NAMA} - {NPM}</span>
    </div>
</nav>
<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">{{{{ session('success') }}}}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $err)<li>{{{{ $err }}}}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</div>
</body>
</html>
""")

for e in E:
    r=e["r"]; L=e["label"]
    th="".join(f"<th>{l}</th>" for _,l,_,_ in e["fields"])
    td=""
    for n,l,t,rel in e["fields"]:
        if t=="select": td+=f"<td>{{{{ $row->{rel[1]}->{rel[2]} ?? '-' }}}}</td>"
        elif t=="date_null": td+=f"<td>{{{{ $row->{n} ?? '-' }}}}</td>"
        else: td+=f"<td>{{{{ $row->{n} }}}}</td>"
    w(f"resources/views/{r}/index.blade.php", f"""@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Data {L}</h4>
    <a href="{{{{ route('{r}.create') }}}}" class="btn btn-primary">Tambah</a>
</div>
<table class="table table-bordered table-striped">
    <thead><tr><th>#</th>{th}<th width="160">Aksi</th></tr></thead>
    <tbody>
    @forelse ($items as $row)
        <tr>
            <td>{{{{ $loop->iteration }}}}</td>{td}
            <td>
                <a href="{{{{ route('{r}.edit', $row) }}}}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{{{ route('{r}.destroy', $row) }}}}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="{len(e['fields'])+2}" class="text-center">Belum ada data</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
""")
    form=""
    for n,l,t,rel in e["fields"]:
        if t=="select":
            lst=f"${rel[1]}_list"
            inp=f"""<select name="{n}" class="form-select">
            <option value="">-- Pilih --</option>
            @foreach ({lst} as $o)
                <option value="{{{{ $o->id }}}}" @selected(old('{n}', $item->{n} ?? '') == $o->id)>{{{{ $o->{rel[2]} }}}}</option>
            @endforeach
        </select>"""
        else:
            ty={"text":"text","email":"email","number":"number","date":"date","date_null":"date"}[t]
            inp=f"""<input type="{ty}" name="{n}" class="form-control" value="{{{{ old('{n}', $item->{n} ?? '') }}}}">"""
        form+=f"""    <div class="mb-3">
        <label class="form-label">{l}</label>
        {inp}
    </div>
"""
    for mode,title,action,meth in [("create","Tambah",f"route('{r}.store')",""),("edit","Edit",f"route('{r}.update', $item)","@method('PUT')")]:
        w(f"resources/views/{r}/{mode}.blade.php", f"""@extends('layouts.app')
@section('content')
<h4>{title} {L}</h4>
<form action="{{{{ {action} }}}}" method="POST">
    @csrf {meth}
{form}    <button class="btn btn-primary">Simpan</button>
    <a href="{{{{ route('{r}.index') }}}}" class="btn btn-secondary">Batal</a>
</form>
@endsection
""")

w("routes/web.php", """<?php

use App\\Http\\Controllers\\AnggotaController;
use App\\Http\\Controllers\\BukuController;
use App\\Http\\Controllers\\KategoriController;
use App\\Http\\Controllers\\PeminjamanController;
use Illuminate\\Support\\Facades\\Route;

Route::redirect('/', '/buku');

Route::resource('kategori', KategoriController::class)->except('show');
Route::resource('buku', BukuController::class)->except('show');
Route::resource('anggota', AnggotaController::class)->except('show');
Route::resource('peminjaman', PeminjamanController::class)->except('show');
""")

w("database/seeders/DatabaseSeeder.php", """<?php

namespace Database\\Seeders;

use App\\Models\\Anggota;
use App\\Models\\Buku;
use App\\Models\\Kategori;
use App\\Models\\Peminjaman;
use Illuminate\\Database\\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $fiksi = Kategori::create(['nama' => 'Fiksi']);
        $tek   = Kategori::create(['nama' => 'Teknologi']);

        $b1 = Buku::create(['kategori_id' => $fiksi->id, 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'tahun' => 2005]);
        $b2 = Buku::create(['kategori_id' => $tek->id, 'judul' => 'Belajar Laravel', 'penulis' => 'Penulis A', 'tahun' => 2024]);

        $a1 = Anggota::create(['nama' => 'Budi', 'npm' => '2301001', 'email' => 'budi@example.com']);

        Peminjaman::create(['anggota_id' => $a1->id, 'buku_id' => $b1->id, 'tanggal_pinjam' => '2026-10-20', 'tanggal_kembali' => null]);
    }
}
""")
