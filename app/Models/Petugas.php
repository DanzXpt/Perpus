<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'petugass';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'user_id',      // Relasi ke tabel users
        'nip',          // Nomor Induk Pegawai (Opsional)
        'nama_petugas',
        'no_telp',
        'alamat'
    ];

    /**
     * Relasi ke Model User (Akun Login)
     * Petugas ini "milik" satu User di tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Peminjaman (Jika petugas yang memproses transaksi dicatat)
     * Satu petugas bisa memproses banyak peminjaman
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'petugas_id');
    }
}