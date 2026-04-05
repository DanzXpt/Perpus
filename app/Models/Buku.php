<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'stok',
        'kategori_id',
        'cover' // <--- Pastikan semua ini ada!
    ];
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
