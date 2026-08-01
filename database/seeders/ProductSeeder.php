<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kategori Contoh
        $catMakanan = Category::create(['name' => 'Makanan']);
        $catMinuman = Category::create(['name' => 'Minuman']);
        $catSnack = Category::create(['name' => 'Snack']);

        // 2. Buat Produk Contoh
        Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Nasi Goreng Spesial',
            'price' => 18000,
            'stock' => 25,
        ]);

        Product::create([
            'category_id' => $catMakanan->id,
            'name' => 'Mie Ayam Bakso',
            'price' => 15000,
            'stock' => 30,
        ]);

        Product::create([
            'category_id' => $catMinuman->id,
            'name' => 'Es Teh Manis',
            'price' => 5000,
            'stock' => 50,
        ]);

        Product::create([
            'category_id' => $catMinuman->id,
            'name' => 'Jus Alpukat',
            'price' => 12000,
            'stock' => 20,
        ]);

        Product::create([
            'category_id' => $catSnack->id,
            'name' => 'Keripik Singkong',
            'price' => 8000,
            'stock' => 40,
        ]);
    }
}