<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    private function rules(): array
    {
        return ['nama' => 'required|string|max:255', 'npm' => 'required|string|max:255', 'email' => 'required|email|max:255'];
    }

    public function index()
    {
        $items = Anggota::latest()->get();
        return view('anggota.index', compact('items'));
    }

    public function create()
    {
        return view('anggota.create', []);
    }

    public function store(Request $request)
    {
        Anggota::create($request->validate($this->rules()));
        return redirect()->route('anggota.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Anggota $anggota)
    {
        return view('anggota.edit', ['item' => $anggota]);
    }

    public function update(Request $request, Anggota $anggota)
    {
        $anggota->update($request->validate($this->rules()));
        return redirect()->route('anggota.index')->with('success', 'Data berhasil diubah.');
    }

    public function destroy(Anggota $anggota)
    {
        $anggota->delete();
        return redirect()->route('anggota.index')->with('success', 'Data berhasil dihapus.');
    }
}
