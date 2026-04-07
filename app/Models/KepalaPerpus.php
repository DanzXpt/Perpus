<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaPerpus extends Model
{
    use HasFactory;

    // Nama tabel khusus untuk Kepala
    protected $table = 'kepala_perpus';

    // Kolom yang boleh diisi
    protected $fillable = [
        'user_id',     
        'nip',          
        'nama_lengkap',
        'no_telp',
        'alamat'
    ];

    /**
     * Relasi ke Model User (Akun Login)
     * Artinya: Setiap data Kepala punya 1 akun login
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}