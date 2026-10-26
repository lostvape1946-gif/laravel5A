<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    private function rules(): array
    {
        return ['kategori_id' => 'required|exists:kategoris,id', 'judul' => 'required|string|max:255', 'penulis' => 'required|string|max:255', 'tahun' => 'required|integer|min:1900|max:2100'];
    }

    public function index()
    {
        $items = Buku::with(['kategori'])->latest()->get();
        return view('buku.index', compact('items'));
    }

    public function create()
    {
        return view('buku.create', ['kategori_list' => Kategori::all()]);
    }

    public function store(Request $request)
    {
        Buku::create($request->validate($this->rules()));
        return redirect()->route('buku.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('buku.edit', ['item' => $buku, 'kategori_list' => Kategori::all()]);
    }

    public function update(Request $request, Buku $buku)
    {
        $buku->update($request->validate($this->rules()));
        return redirect()->route('buku.index')->with('success', 'Data berhasil diubah.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Data berhasil dihapus.');
    }
}
