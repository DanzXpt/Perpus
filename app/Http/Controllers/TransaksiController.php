<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // 1. TAMPILKAN DAFTAR TRANSAKSI
    public function index()
    {
        // Update denda dulu sebelum data ditampilkan
        $this->updateDendaOtomatis();

        $transaksi = Peminjaman::with(['user', 'buku'])
            ->latest() // 
            ->paginate(10);

        return view('petugas.transaksi.index', compact('transaksi'));
    }

    // 2. FITUR SETUJU (ACC)
    public function setuju($id)
    {
        $transaksi = Peminjaman::findOrFail($id);

        // Cek stok buku
        if ($transaksi->buku->stok <= 0) {
            return back()->with('error', 'Gagal! Stok buku habis.');
        }

        $transaksi->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => Carbon::now(),
            'jatuh_tempo' => Carbon::now()->addDays(7), // Pinjam 1 minggu
        ]);

        // Kurangi stok buku secara otomatis
        $transaksi->buku->decrement('stok');

        return back()->with('success', 'Pengajuan disetujui, buku berhasil dipinjam.');
    }

    // 3. FITUR TOLAK
    public function tolak($id)
    {
        $transaksi = Peminjaman::findOrFail($id);

        $transaksi->update([
            'status' => 'ditolak'
        ]);

        return back()->with('error', 'Pengajuan peminjaman berhasil ditolak.');
    }

    // 4. KONFIRMASI PENGEMBALIAN BUKU
    public function kembalikanBuku($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $hariIni = Carbon::today();
        $jatuhTempo = Carbon::parse($pinjam->jatuh_tempo);

        $denda = 0;
        $telat = 0;

        // Hitung denda final saat kembali
        if ($hariIni->gt($jatuhTempo)) {
            $telat = $jatuhTempo->diffInDays($hariIni);
            $denda = $telat * 1000;
        }

        $pinjam->update([
            'status' => 'kembali',
            'tanggal_kembali' => $hariIni,
            'denda' => $denda,
            'sisa_denda' => $denda,
            'status_denda' => $denda > 0 ? 'nunggak' : 'lunas'
        ]);

        // Tambah stok buku kembali
        $pinjam->buku->increment('stok');

        return back()->with('success', 'Buku berhasil dikembalikan. Telat: ' . $telat . ' Hari.');
    }

    // 5. UPDATE DENDA OTOMATIS (RUNNING BACKGROUND)
    public function updateDendaOtomatis()
    {
        $today = Carbon::today();
        $dendaPerHari = 1000;

        // Ambil yang sedang dipinjam atau sudah telat tapi belum kembali
        $peminjaman = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])
            ->whereNotNull('jatuh_tempo')
            ->get();

        foreach ($peminjaman as $p) {
            $jatuhTempo = Carbon::parse($p->jatuh_tempo);

            if ($today->gt($jatuhTempo)) {
                $hari = $jatuhTempo->diffInDays($today);
                $totalDendaSekarang = $hari * $dendaPerHari;

                // Hitung cicilan pembayaran yang mungkin sudah masuk
                $sudahBayar = ($p->denda ?? 0) - ($p->sisa_denda ?? 0);

                $p->update([
                    'status' => 'terlambat',
                    'denda' => $totalDendaSekarang,
                    'sisa_denda' => max(0, $totalDendaSekarang - $sudahBayar),
                    'status_denda' => 'nunggak'
                ]);
            }
        }
    }
}