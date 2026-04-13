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
        $this->updateDendaOtomatis();

        $transaksi = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(10);

        return view('petugas.transaksi.index', compact('transaksi'));
    }

    // konfirmasi pengembalian + hitung denda
    public function kembalikanBuku($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $hariIni = Carbon::today();
        $jatuhTempo = Carbon::parse($pinjam->jatuh_tempo);

        $denda = 0;
        $telat = 0;

        if ($hariIni->gt($jatuhTempo)) {
            $telat = $jatuhTempo->diffInDays($hariIni);
            $denda = $telat * 1000;
        }

        $pinjam->update([
            'status' => 'kembali',
            'tanggal_kembali' => $hariIni,
            'denda' => $denda,
            'dibayar' => 0,
            'sisa_denda' => $denda,
            'status_denda' => $denda > 0 ? 'nunggak' : 'lunas'
        ]);

        $pinjam->buku->increment('stok');

        return back()->with(
            'success',
            'Terlambat ' . $telat . ' hari, Denda Rp ' . number_format($denda, 0, ',', '.')
        );
    }

    public function updateDendaOtomatis()
    {
        $today = Carbon::today();
        $dendaPerHari = 1000;

        $peminjaman = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])
            ->whereNotNull('jatuh_tempo')
            ->get();

        foreach ($peminjaman as $p) {

            $jatuhTempo = Carbon::parse($p->jatuh_tempo);

            if ($today->gt($jatuhTempo)) {

                $hari = $jatuhTempo->diffInDays($today);
                $denda = $hari * $dendaPerHari;

                $p->update([
                    'status' => 'terlambat',
                    'denda' => $denda,
                    'sisa_denda' => $denda,
                    'status_denda' => 'nunggak'
                ]);
            }
        }
    }
}