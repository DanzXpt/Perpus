<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Buku;
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
        $data_buku = \App\Models\Buku::all();

        // 2. Kirim variabelnya lewat compact
        // Nama di dalam compact('...') HARUS SAMA dengan nama variabel di atas tanpa tanda $
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kepala.laporan.buku_pdf', compact('data_buku'));

        // 3. Tampilkan
        return $pdf->stream('laporan-buku.pdf');
    }

    // Fungsi Cetak Laporan Akun
    public function cetakAkun()
    {
        $users = User::all();

        $pdf = Pdf::loadView('kepala.laporan.akun_pdf', compact('users'));

        return $pdf->stream('laporan-data-akun.pdf');
    }
}