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
        'cover',
        'name',
        'email',
        'password',
        'role',
        'no_telp',
        'alamat'
    ];
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function anggota()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
