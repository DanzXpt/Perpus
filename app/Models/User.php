<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens; // Tambahkan ini jika pakai Laravel Starter Kit

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nis',      // Tambahkan ini
        'kelas',    // Tambahkan ini
        'alamat',   // Tambahkan ini
        'nip',      // Tambahkan ini
        'no_hp',    // Tambahkan ini
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Penting: Memastikan password otomatis di-hash jika pakai Laravel versi terbaru
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ============================================================
    // HELPER FUNCTIONS (Biar cek role di Controller/Blade simpel)
    // ============================================================


    // ============================================================
    // RELASI PROFIL (TEKNIK TERPISAH)
    // ============================================================

    public function anggota()
    {
        return $this->hasOne(Anggota::class, 'user_id');
    }

    public function petugas()
    {
        return $this->hasOne(Petugas::class, 'user_id');
    }

    public function kepala()
    {
        return $this->hasOne(KepalaPerpus::class, 'user_id');
    }

    // ============================================================
    // RELASI TRANSAKSI
    // ============================================================

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }
}