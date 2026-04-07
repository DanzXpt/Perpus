<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman; // Pastikan kamu sudah buat model Peminjaman
use Carbon\Carbon;


class PetugasController extends Controller
{
    /**
     * Dashboard Utama Petugas (Tampilan Statistik)
     */
    public function index()
    {
        // 1. Hitung Total Buku & Anggota
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'anggota')->count();

        // 2. Hitung Status Peminjaman
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();

        // 3. Logika Terlambat (Berdasarkan tanggal jatuh tempo yang sudah lewat)
        $totalTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::today())
            ->count();

        // 4. Hitung Total Denda yang sudah dibayar
        $totalDenda = Peminjaman::where('status', 'kembali')->sum('denda');

        // 5. Ambil 5 Transaksi Terbaru untuk Tabel
        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->take(5)
            ->get();

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
     * Menampilkan daftar pengajuan peminjaman/pengembalian
     */
    public function transaksi()
    {
        $transaksi = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(10);

        return view('petugas.transaksi.index', compact('transaksi'));
    }

    public function daftarPengajuan()
    {
        // Ambil data yang statusnya masih 'pending'
        $pengajuan = Peminjaman::with(['user', 'buku'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('petugas.pengajuan.index', compact('pengajuan'));
    }

    public function setujuiPengajuan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(7)
        ]);

        // kurangi stok buku
        $peminjaman->buku->decrement('stok');

        return back()->with('success', 'Pengajuan berhasil disetujui');
    }

    public function tolakPengajuan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => 'ditolak'
        ]);

        return back()->with('success', 'Pengajuan ditolak');
    }
}