@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Data Buku</h4>
    <a href="{{ route('buku.create') }}" class="btn btn-primary">Tambah</a>
</div>
<table class="table table-bordered table-striped">
    <thead><tr><th>#</th><th>Kategori</th><th>Judul</th><th>Penulis</th><th>Tahun</th><th width="160">Aksi</th></tr></thead>
    <tbody>
    @forelse ($items as $row)
        <tr>
            <td>{{ $loop->iteration }}</td><td>{{ $row->kategori->nama ?? '-' }}</td><td>{{ $row->judul }}</td><td>{{ $row->penulis }}</td><td>{{ $row->tahun }}</td>
            <td>
                <a href="{{ route('buku.edit', $row) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('buku.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="6" class="text-center">Belum ada data</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
