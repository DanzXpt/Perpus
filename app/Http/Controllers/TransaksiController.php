<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman; // Pastikan Model Peminjaman sudah ada
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function riwayat()
    {
        // Ambil data peminjaman milik user yang sedang login
        $riwayat = Peminjaman::where('user_id', Auth::user()->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('anggota.riwayat', compact('riwayat'));
    }

    public function index()
    {
        // Ambil semua data peminjaman beserta data user dan bukunya (Eager Loading)
        $peminjaman = Peminjaman::with(['user', 'buku'])->latest()->paginate(10);

        return view('petugas.transaksi.index', compact('peminjaman'));
    }
}