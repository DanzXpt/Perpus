<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'anggota';

    protected $fillable = [
        'user_id',
        'nis',
        'nama_lengkap',
        'no_hp',
        'kelas',
        'alamat',
    ];
    protected $guarded = [];

    /**
     * Relasi ke Model User (Akun Login)
     * Satu Anggota memiliki satu akun di tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Peminjaman
     * Satu Anggota bisa melakukan banyak peminjaman buku
     */
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'anggota_id');
    }

}