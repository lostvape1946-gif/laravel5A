<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    private function rules(): array
    {
        return ['anggota_id' => 'required|exists:anggotas,id', 'buku_id' => 'required|exists:bukus,id', 'tanggal_pinjam' => 'required|date', 'tanggal_kembali' => 'nullable|date'];
    }

    public function index()
    {
        $items = Peminjaman::with(['anggota', 'buku'])->latest()->get();
        return view('peminjaman.index', compact('items'));
    }

    public function create()
    {
        return view('peminjaman.create', ['anggota_list' => Anggota::all(), 'buku_list' => Buku::all()]);
    }

    public function store(Request $request)
    {
        Peminjaman::create($request->validate($this->rules()));
        return redirect()->route('peminjaman.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Peminjaman $peminjaman)
    {
        return view('peminjaman.edit', ['item' => $peminjaman, 'anggota_list' => Anggota::all(), 'buku_list' => Buku::all()]);
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $peminjaman->update($request->validate($this->rules()));
        return redirect()->route('peminjaman.index')->with('success', 'Data berhasil diubah.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Data berhasil dihapus.');
    }
}
