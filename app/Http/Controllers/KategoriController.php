<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    private function rules(): array
    {
        return ['nama' => 'required|string|max:255'];
    }

    public function index()
    {
        $items = Kategori::latest()->get();
        return view('kategori.index', compact('items'));
    }

    public function create()
    {
        return view('kategori.create', []);
    }

    public function store(Request $request)
    {
        Kategori::create($request->validate($this->rules()));
        return redirect()->route('kategori.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', ['item' => $kategori]);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $kategori->update($request->validate($this->rules()));
        return redirect()->route('kategori.index')->with('success', 'Data berhasil diubah.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Data berhasil dihapus.');
    }
}
