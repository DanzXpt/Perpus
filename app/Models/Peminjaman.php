<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    // protected $guarded = [];

    // TAMBAHKAN BARIS INI:
    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'denda'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    // Tambahkan juga relasi ke User agar di halaman petugas tidak error
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'tanggal_pinjam' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];
}