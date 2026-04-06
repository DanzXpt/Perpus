<?php

namespace App\Http\Controllers;

use App\Models\Transaksi; // Atau Peminjaman, sesuaikan nama Modelmu
use App\Models\Buku;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    // 1. Menampilkan Tabel Transaksi
    public function index()
    {
        // Ambil data peminjaman, urutkan dari yang terbaru
        $peminjaman = Transaksi::with(['user', 'buku'])->latest()->paginate(10);
        return view('petugas.transaksi.index', compact('peminjaman'));
    }

    // 2. Proses Pengembalian Buku
    public function kembali($id)
    {
        // Cari data transaksi berdasarkan ID
        $item = Transaksi::findOrFail($id);

        // Update Status & Tanggal Kembali
        $item->update([
            'status' => 'kembali',
            'tgl_kembali' => now(), // Mengisi tanggal hari ini otomatis
        ]);

        // Tambah Stok Buku (Karena buku sudah balik)
        if ($item->buku) {
            $item->buku->increment('stok');
        }

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }
}