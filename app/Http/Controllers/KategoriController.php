<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = \App\Models\Kategori::all();
        return view('petugas.kategori.index', compact('kategori'));
    }
}
