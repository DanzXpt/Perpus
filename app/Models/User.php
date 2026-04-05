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
        'role', // admin, petugas, anggota
        'telepon',
        'alamat',
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

    public function isKepala()
    {
        return $this->role === 'admin'; // atau 'kepala', sesuaikan DB
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isAnggota()
    {
        return $this->role === 'anggota';
    }

    // ============================================================
    // RELASI PROFIL (TEKNIK TERPISAH)
    // ============================================================

    public function profilAnggota()
    {
        return $this->hasOne(Anggota::class, 'user_id');
    }

    public function profilPetugas()
    {
        return $this->hasOne(Petugas::class, 'user_id');
    }

    public function profilKepala()
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