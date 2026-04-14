<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepalaPerpus extends Model
{
    use HasFactory;

    protected $table = 'kepala_perpus';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'no_hp',
    ];

    /**
     * Relasi ke User (akun login)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}