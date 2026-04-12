<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      //   User::factory(10)->create();

      //   User::factory()->create([
      //       'name' => 'Test User',
      //       'email' => 'test@example.com',
      //   ]);
        

        // Angota
        User::create([
           "name"   =>    "Ahdannnn",
           "email"  =>    "ahdan@gmail.com",
           "password"=> bcrypt("12345678"),
           "role"   =>   "anggota"
        ]);
        // Petugas
        User::create([
           "name"   =>    "Gilang",
           "email"  =>    "gilang@gmail.com",
           "password"=> bcrypt("12345678"),
           "role"   =>   "petugas"
        ]);
        // kepala
        User::create([
           "name"   =>    "Hols",
           "email"  =>    "hols@gmail.com",
           "password"=> bcrypt("12345678"),
           "role"   =>   "kepala"
        ]);

      Kategori::create([
         "nama_kategori"   =>   "Fiksi"
      ]);
      Kategori::create([
         "nama_kategori"   =>   "Tech"
      ]);
      Kategori::create([
         "nama_kategori"   =>   "non-fiksi"
      ]);

    }
}
