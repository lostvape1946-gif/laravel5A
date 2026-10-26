<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $fiksi = Kategori::create(['nama' => 'Fiksi']);
        $tek   = Kategori::create(['nama' => 'Teknologi']);

        $b1 = Buku::create(['kategori_id' => $fiksi->id, 'judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'tahun' => 2005]);
        $b2 = Buku::create(['kategori_id' => $tek->id, 'judul' => 'Belajar Laravel', 'penulis' => 'Penulis A', 'tahun' => 2024]);

        $a1 = Anggota::create(['nama' => 'Budi', 'npm' => '2301001', 'email' => 'budi@example.com']);

        Peminjaman::create(['anggota_id' => $a1->id, 'buku_id' => $b1->id, 'tanggal_pinjam' => '2026-10-20', 'tanggal_kembali' => null]);
    }
}
