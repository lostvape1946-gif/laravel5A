@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Data Kategori</h4>
    <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah</a>
</div>
<table class="table table-bordered table-striped">
    <thead><tr><th>#</th><th>Nama Kategori</th><th width="160">Aksi</th></tr></thead>
    <tbody>
    @forelse ($items as $row)
        <tr>
            <td>{{ $loop->iteration }}</td><td>{{ $row->nama }}</td>
            <td>
                <a href="{{ route('kategori.edit', $row) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('kategori.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
