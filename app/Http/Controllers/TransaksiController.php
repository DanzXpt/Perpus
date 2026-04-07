<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Buku;
use Carbon\Carbon;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // 1. Menampilkan Tabel Transaksi
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'buku'])->latest()->paginate(10);
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

    public function cetak()
    {
        $transaksi = Peminjaman::with('user', 'buku')->latest()->get();

        $pdf = Pdf::loadView('petugas.transaksi.cetak', compact('transaksi'));
        return $pdf->stream('laporan-transaksi.pdf');

    }
}