<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // 1. Tentukan nama tabel secara manual biar nggak bingung nyari 'kategoris'
    protected $table = 'kategoris';

    // 2. Daftarkan kolom yang boleh diisi
    protected $fillable = [
        'nama_kategori'
    ];

    /**
     * Relasi: Satu Kategori bisa punya banyak Buku
     */
    public function bukus()
    {
        // Hubungkan ke Model Buku
        return $this->hasMany(Buku::class, 'kategori_id');
    }
}