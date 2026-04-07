<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Transaksi; // Atau Peminjaman, sesuaikan nama Modelmu
use App\Models\Buku;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    // 1. Menampilkan Tabel Transaksi
    public function index()
    {
        // Ambil data peminjaman, urutkan dari yang terbaru
        $peminjaman = Transaksi::with(['user', 'buku'])->latest()->paginate(10);
        return view('petugas.dashboard.index', compact('peminjaman'));
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
            $denda = $selisih_hari * 1000; 
        }

        $pinjam->update([
            'status' => 'kembali',
            'tanggal_realisasi' => $tgl_sekarang,
            'denda' => $denda
        ]);

        // Tambah stok buku kembali karena sudah dikembalikan
        $pinjam->buku->increment('stok');

        return back()->with('success', 'Buku berhasil dikembalikan! Denda: Rp ' . number_format($denda));
    }
}