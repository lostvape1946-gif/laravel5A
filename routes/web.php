<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/buku');

Route::resource('kategori', KategoriController::class)->except('show');
Route::resource('buku', BukuController::class)->except('show');
Route::resource('anggota', AnggotaController::class)->except('show');
Route::resource('peminjaman', PeminjamanController::class)->except('show');
