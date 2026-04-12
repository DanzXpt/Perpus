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
        'nis',
        'kelas',
        'alamat',
        'nip',
        'no_hp'
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
        return $this->hasOne(Anggota::class);
    }

    public function petugas()
    {
        return $this->hasOne(Petugas::class);
    }

    public function kepala()
    {
        return $this->hasOne(KepalaPerpus::class);
    }

    // app/Models/User.php


    // ============================================================
    // RELASI TRANSAKSI
    // ============================================================

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }
}