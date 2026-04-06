<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'user_id',
        'buku_id',
        'tgl_pinjam',
        'tgl_kembali',
        'status'
    ];

    // Relasi ke User (Siapa yang pinjam)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Buku (Buku apa yang dipinjam)
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}