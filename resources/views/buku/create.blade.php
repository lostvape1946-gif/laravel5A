@extends('layouts.app')
@section('content')
<h4>Tambah Buku</h4>
<form action="{{ route('buku.store') }}" method="POST">
    @csrf 
    <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="kategori_id" class="form-select">
            <option value="">-- Pilih --</option>
            @foreach ($kategori_list as $o)
                <option value="{{ $o->id }}" @selected(old('kategori_id', $item->kategori_id ?? '') == $o->id)>{{ $o->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Judul</label>
        <input type="text" name="judul" class="form-control" value="{{ old('judul', $item->judul ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Penulis</label>
        <input type="text" name="penulis" class="form-control" value="{{ old('penulis', $item->penulis ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Tahun</label>
        <input type="number" name="tahun" class="form-control" value="{{ old('tahun', $item->tahun ?? '') }}">
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('buku.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
