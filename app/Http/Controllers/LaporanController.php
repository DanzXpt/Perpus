<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // Halaman pusat laporan (yang isinya kartu-kartu pilihan)
    public function index()
    {
        return view('kepala.laporan.index');
    }

    // Fungsi Cetak Laporan Buku
    public function cetakBuku()
    {
        // 1. Ambil datanya dulu dari database (Pastikan Model Buku sudah di-import di atas)
        $data_buku = Buku::all();

        // 2. Kirim variabelnya lewat compact
        // Nama di dalam compact('...') HARUS SAMA dengan nama variabel di atas tanpa tanda $
        $pdf = Pdf::loadView('kepala.laporan.buku_pdf', compact('data_buku'));

        // 3. Tampilkan
        return $pdf->stream('laporan-buku.pdf');
    }

    // Fungsi Cetak Laporan Akun
    public function cetakAkun()
    {
        $user = User::all();
        // $user = User::orderBy('id', 'asc')->get();

        $pdf = Pdf::loadView('kepala.laporan.akun_pdf', compact('user'));

        return $pdf->stream('laporan-data-akun.pdf');
    }


    // Halaman dashboard laporan khusus Petugas
    public function indexPetugas()
    {
        // Gunakan paginate agar tidak error di bagian {{ $transaksi->total() }}
        $transaksi = Peminjaman::with(['user', 'buku'])->latest()->paginate(10);

        return view('petugas.transaksi.index', compact('transaksi'));
    }

    public function peminjamanBuku()
    {
        // Ambil SEMUA data tanpa pagination untuk laporan PDF
        $transaksi = Peminjaman::with(['user', 'buku'])->latest()->get();

        // JANGAN diarahkan ke 'petugas.transaksi.index' karena isinya ada Sidebar/Navbar
        // Buat file baru khusus PDF agar tidak error total()
        $pdf = Pdf::loadView('petugas.transaksi.cetak_pdf', compact('transaksi'));

        return $pdf->stream('laporan-transaksi.pdf');
    }
}