<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
{
    $kategori = \App\Models\Kategori::all();
    // Pastikan nama file view-nya sesuai (petugas/kategori/index.blade.php)
    return view('petugas.kategori.index', compact('kategori'));
}
}
