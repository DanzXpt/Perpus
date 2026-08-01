<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name' => 'Ahdan Muzaki',
            'email' => 'admin@pos.com', // Atau gunakan username jika kolomnya username
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Akun Kasir
        User::create([
            'name' => 'Kasir Toko',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);
    }
}