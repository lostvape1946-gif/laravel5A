@extends('layouts.app')
@section('content')
<h4>Tambah Kategori</h4>
<form action="{{ route('kategori.store') }}" method="POST">
    @csrf 
    <div class="mb-3">
        <label class="form-label">Nama Kategori</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $item->nama ?? '') }}">
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
