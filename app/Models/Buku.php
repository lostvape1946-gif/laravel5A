<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'bukus';
    protected $fillable = ['kategori_id', 'judul', 'penulis', 'tahun'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }
}
