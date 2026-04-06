<?php

namespace Database\Seeders;

use App\Models\Buku;
use Illuminate\Database\Seeder;

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        Buku::create([
            'judul'         => 'Belajar Laravel 11 untuk Pemula',
            'kategori_id'   =>  1,
            'penulis'       => 'Ahdan Muzaki',
            'penerbit'      => 'Informatika',
            'tahun_terbit'  => 2024,
            'stok'          => 10,
            'cover'         => null, // Sementara kosongkan dulu
        ]);

        Buku::create([
            'judul'         => 'Logika Pemrograman Javascript',
            'kategori_id'   =>  1,
            'penulis'       => 'RPL Expert',
            'penerbit'      => 'Erlangga',
            'tahun_terbit'  => 2023,
            'stok'          => 5,
            'cover'         => null,
        ]);

        Buku::create([
            'judul'         => 'Membangun Web dengan Tailwind CSS',
            'kategori_id'   =>  1,
            'penulis'       => 'Creative Studio',
            'penerbit'      => 'Media Kita',
            'tahun_terbit'  => 2024,
            'stok'          => 3,
            'cover'         => null,
        ]);
    }
}