@extends('layouts.app')
@section('content')
<h4>Edit Anggota</h4>
<form action="{{ route('anggota.update', $item) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $item->nama ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">NPM</label>
        <input type="text" name="npm" class="form-control" value="{{ old('npm', $item->npm ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $item->email ?? '') }}">
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('anggota.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
