@extends('layouts.app')
@section('content')
<h4>Edit Peminjaman</h4>
<form action="{{ route('peminjaman.update', $item) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">Anggota</label>
        <select name="anggota_id" class="form-select">
            <option value="">-- Pilih --</option>
            @foreach ($anggota_list as $o)
                <option value="{{ $o->id }}" @selected(old('anggota_id', $item->anggota_id ?? '') == $o->id)>{{ $o->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Buku</label>
        <select name="buku_id" class="form-select">
            <option value="">-- Pilih --</option>
            @foreach ($buku_list as $o)
                <option value="{{ $o->id }}" @selected(old('buku_id', $item->buku_id ?? '') == $o->id)>{{ $o->judul }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Tgl Pinjam</label>
        <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', $item->tanggal_pinjam ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Tgl Kembali</label>
        <input type="date" name="tanggal_kembali" class="form-control" value="{{ old('tanggal_kembali', $item->tanggal_kembali ?? '') }}">
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
