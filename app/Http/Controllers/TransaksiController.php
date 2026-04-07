<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // tampilkan daftar transaksi
    public function index()
    {
        $transaksi = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(10);

        return view('petugas.transaksi.index', compact('transaksi'));
    }

    // konfirmasi pengembalian + hitung denda
    public function kembalikanBuku($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $tgl_kembali = Carbon::parse($pinjam->tanggal_kembali);
        $tgl_sekarang = Carbon::today();

        $denda = 0;

        if ($tgl_sekarang > $tgl_kembali) {
            $selisih_hari = $tgl_sekarang->diffInDays($tgl_kembali);
            $denda = $selisih_hari * 1000;
        }

        $pinjam->update([
            'status' => 'kembali',
            'tanggal_realisasi' => $tgl_sekarang,
            'denda' => $denda
        ]);

        $pinjam->buku->increment('stok');

        return back()->with('success', 'Buku berhasil dikembalikan! Denda: Rp ' . number_format($denda));
    }

    // cetak pdf
    public function cetak()
    {
        $transaksi = Peminjaman::with('user', 'buku')->latest()->get();

        $pdf = Pdf::loadView('petugas.transaksi.cetak', compact('transaksi'));

        return $pdf->stream('laporan-transaksi.pdf');
    }
}