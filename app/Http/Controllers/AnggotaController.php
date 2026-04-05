<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Kategori;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnggotaController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Statistik untuk Card
        $totalPinjam = Peminjaman::where('user_id', $userId)->count();
        $sedangDipinjam = Peminjaman::where('user_id', $userId)->where('status', 'dipinjam')->count();
        $terlambat = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::today())
            ->count();
        $totalDenda = Peminjaman::where('user_id', $userId)->sum('denda');

        // Data Tabel Peminjaman Aktif
        $peminjamanAktif = Peminjaman::with('buku')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->get();

        return view('anggota.dashboard', compact(
            'totalPinjam',
            'sedangDipinjam',
            'terlambat',
            'totalDenda',
            'peminjamanAktif'
        ));
    }

    public function buku(Request $request)
    {
        $query = Buku::with('kategori');

        // Logic Pencarian
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                ->orWhere('penulis', 'like', '%' . $request->search . '%');
        }

        // Logic Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $buku = $query->get();
        $kategoris = Kategori::all();

        return view('anggota.buku', compact('buku', 'kategoris'));
    }

    public function riwayat()
    {
        // 1. Ambil data peminjaman milik user yang sedang login
        $riwayat = Peminjaman::with('buku')
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Lempar ke view riwayat milik anggota
        return view('anggota.riwayat', compact('riwayat'));
    }
}