<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan - NAMA_ANDA (NPM_ANDA)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand navbar-dark bg-dark">
    <div class="container">
        <span class="navbar-brand">Perpustakaan</span>
        <ul class="navbar-nav me-auto"><li class="nav-item"><a class="nav-link" href="{{ route('kategori.index') }}">Kategori</a></li><li class="nav-item"><a class="nav-link" href="{{ route('buku.index') }}">Buku</a></li><li class="nav-item"><a class="nav-link" href="{{ route('anggota.index') }}">Anggota</a></li><li class="nav-item"><a class="nav-link" href="{{ route('peminjaman.index') }}">Peminjaman</a></li></ul>
        <span class="navbar-text">NAMA_ANDA - NPM_ANDA</span>
    </div>
</nav>
<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
</div>
</body>
</html>
