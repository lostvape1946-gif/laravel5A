@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Data Anggota</h4>
    <a href="{{ route('anggota.create') }}" class="btn btn-primary">Tambah</a>
</div>
<table class="table table-bordered table-striped">
    <thead><tr><th>#</th><th>Nama</th><th>NPM</th><th>Email</th><th width="160">Aksi</th></tr></thead>
    <tbody>
    @forelse ($items as $row)
        <tr>
            <td>{{ $loop->iteration }}</td><td>{{ $row->nama }}</td><td>{{ $row->npm }}</td><td>{{ $row->email }}</td>
            <td>
                <a href="{{ route('anggota.edit', $row) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('anggota.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-center">Belum ada data</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
