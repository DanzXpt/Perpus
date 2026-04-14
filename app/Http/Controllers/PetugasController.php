<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PetugasController extends Controller
{
    /**
     * Dashboard Utama Petugas
     */
    public function dashboard()
    {
        $today = now();

        // 1. UPDATE DATA TERLAMBAT OTOMATIS
        // Mengambil data yang masih dipinjam, belum lunas, dan sudah melewati jatuh tempo
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('status_denda', '!=', 'lunas')
            ->whereDate('jatuh_tempo', '<', $today)
            ->get();

        foreach ($peminjamanTerlambat as $p) {
            // Hitung selisih hari keterlambatan
            $hari = $today->diffInDays($p->jatuh_tempo);
            $denda = $hari * 1000;

            $p->update([
                'status' => 'terlambat',
                'denda' => $denda,
                'sisa_denda' => 0, // Jika sistem kamu pakai cicilan, ini bisa disesuaikan
                'status_denda' => 'nunggak'
            ]);
        }

        // 2. HITUNG STATISTIK UNTUK DASHBOARD
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'anggota')->count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();

        // Pastikan menghitung status 'terlambat' yang baru saja diupdate di atas
        $totalTerlambat = Peminjaman::where('status', 'terlambat')->count();

        // Menghitung total denda yang belum dibayar (nunggak)
        $totalDenda = Peminjaman::where('status_denda', 'nunggak')->sum('denda');

        // 3. AMBIL RIWAYAT TRANSAKSI TERBARU
        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->take(5)
            ->get();

        // 4. KIRIM SEMUA DATA KE VIEW (Hanya satu return)
        return view('petugas.dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'totalDipinjam',
            'totalTerlambat',
            'totalDenda',
            'peminjamanTerbaru'
        ));
    }

    /**
     * Halaman Manajemen Denda
     */
    public function indexDenda()
    {
        // AMBIL SEMUA: Agar tab Alpine.js bisa memfilter nunggak vs lunas
        $denda = Peminjaman::with(['user', 'buku'])
            ->where('denda', '>', 0)
            ->latest()
            ->get();

        return view('petugas.denda.index', compact('denda'));
    }

    /**
     * Proses Pelunasan Denda
     */
    public function bayarLunas($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->denda <= 0) {
            return back()->with('info', 'Data denda tidak valid.');
        }

        // Update ke database
        $peminjaman->update([
            'dibayar' => $peminjaman->denda,
            'sisa_denda' => 0,
            'status_denda' => 'lunas'
        ]);

        return back()->with('success', 'Berhasil! Denda ' . $peminjaman->user->name . ' dinyatakan Lunas.');
    }
}