@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Data Peminjaman</h4>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">Tambah</a>
</div>
<table class="table table-bordered table-striped">
    <thead><tr><th>#</th><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th width="160">Aksi</th></tr></thead>
    <tbody>
    @forelse ($items as $row)
        <tr>
            <td>{{ $loop->iteration }}</td><td>{{ $row->anggota->nama ?? '-' }}</td><td>{{ $row->buku->judul ?? '-' }}</td><td>{{ $row->tanggal_pinjam }}</td><td>{{ $row->tanggal_kembali ?? '-' }}</td>
            <td>
                <a href="{{ route('peminjaman.edit', $row) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('peminjaman.destroy', $row) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
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
