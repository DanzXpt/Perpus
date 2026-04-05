<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Peminjaman; // Pastikan kamu sudah buat model Peminjaman
use Illuminate\Http\Request;
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
        $totalAnggota = User::where('level', 'anggota')->count();

        // 2. Hitung Status Peminjaman
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        
        // 3. Logika Terlambat (Berdasarkan tanggal jatuh tempo yang sudah lewat)
        $totalTerlambat = Peminjaman::where('status', 'dipinjam')
                            ->where('tanggal_kembali', '<', Carbon::today())
                            ->count();

        // 4. Hitung Total Denda yang sudah dibayar
        $totalDenda = Peminjaman::where('status', 'dikembalikan')->sum('denda');

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
        $transaksi = Peminjaman::with(['user', 'buku'])->latest()->paginate(10);
        return view('petugas.transaksi.index', compact('transaksi'));
    }

    /**
     * Proses Validasi Pengembalian & Hitung Denda Otomatis
     */
    public function kembalikanBuku($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $tgl_kembali = Carbon::parse($pinjam->tanggal_kembali);
        $tgl_sekarang = Carbon::today();
        
        $denda = 0;
        
        // Jika terlambat (lebih dari tgl jatuh tempo)
        if ($tgl_sekarang > $tgl_kembali) {
            $selisih_hari = $tgl_sekarang->diffInDays($tgl_kembali);
            $denda = $selisih_hari * 1000; // Misal denda 1000 per hari
        }

        $pinjam->update([
            'status' => 'dikembalikan',
            'tanggal_realisasi' => $tgl_sekarang,
            'denda' => $denda
        ]);

        // Tambah stok buku kembali karena sudah dikembalikan
        $pinjam->buku->increment('stok');

        return back()->with('success', 'Buku berhasil dikembalikan! Denda: Rp ' . number_format($denda));
    }
}