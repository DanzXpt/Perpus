<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'jatuh_tempo',
        'tanggal_kembali',
        'status',
        'denda',
        'dibayar',
        'sisa_denda',
        'status_denda'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'jatuh_tempo' => 'date',
        'tanggal_kembali' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    // app/Models/Peminjaman.php

    public function getTerlambatAttribute()
    {
        return $this->denda > 0 ? 'Terlambat' : 'Tepat Waktu';
    }

    // Tambahkan ini di dalam class Peminjaman
    // File: App\Models\Peminjaman.php

    public function getStatusDendaAttribute($value)
    {
        // Jika sisa denda sudah 0 tapi denda awalnya ada, berarti LUNAS
        if ($this->sisa_denda <= 0 && $this->denda > 0) {
            return 'lunas';
        }

        // Jika masih ada sisa denda, berarti NUNGGAK
        if ($this->sisa_denda > 0) {
            return 'nunggak';
        }

        return $value ?? '-';
    }

    // Tambahkan atau update fungsi ini di Model Peminjaman.php
    public function getStatusLabelAttribute()
    {
        // Jika masih dipinjam tapi sudah lewat jatuh tempo
        if ($this->status === 'dipinjam' && now()->startOfDay() > \Carbon\Carbon::parse($this->jatuh_tempo)->startOfDay()) {
            return 'TERLAMBAT';
        }

        return strtoupper($this->status);
    }

}