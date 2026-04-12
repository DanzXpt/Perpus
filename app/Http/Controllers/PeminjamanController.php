<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Anggota mengajukan pinjam
    public function store(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        // Tetap cek stok di awal agar anggota tidak bisa pencet tombol jika stok 0
        if ($buku->stok <= 0) {
            return redirect()->back()->with('error', 'Stok buku sedang kosong.');
        }

        try {
            $kodeTransaksi = 'TRX-' . date('Ymd') . rand(1000, 9999);

            Peminjaman::create([
                'kode_transaksi' => $kodeTransaksi,
                'user_id' => auth()->id(),
                'buku_id' => $id,
                'tanggal_pinjam' => now(),
                'jatuh_tempo' => now()->addDays(4),
                'status' => 'pending', // Status masih pending
                'denda' => 0,
                'dibayar' => 0,
                'sisa_denda' => 0,
                'status_denda' => '-',
            ]);

            // JANGAN KURANGI STOK DI SINI
            return redirect()->back()->with('success', 'Pengajuan peminjaman dikirim. Menunggu persetujuan petugas.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }


    public function setujui($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status != 'pending') {
            return back()->with('error', 'Pengajuan sudah diproses');
        }

        $buku = Buku::findOrFail($peminjaman->buku_id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis');
        }

        // ubah status
        $peminjaman->update([
            'status' => 'dipinjam'
        ]);

        // kurangi stok buku
        $buku->decrement('stok');

        return back()->with('success', 'Pengajuan disetujui dan stok berkurang');
    }

    // Petugas menolak pengajuan
    public function tolak($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => 'ditolak'
        ]);

        return back()->with('success', 'Pengajuan ditolak');
    }

    // Petugas konfirmasi pengembalian + hitung denda
    public function konfirmasi($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $tanggalKembali = Carbon::today();
        $jatuhTempo = Carbon::parse($peminjaman->jatuh_tempo);

        $telat = 0;
        $denda = 0;

        if ($tanggalKembali->gt($jatuhTempo)) {
            $telat = $jatuhTempo->diffInDays($tanggalKembali);
            $denda = $telat * 1000;
        }

        $peminjaman->status = 'kembali';
        $peminjaman->tanggal_kembali = $tanggalKembali;
        $peminjaman->denda = $denda;
        $peminjaman->dibayar = 0;
        $peminjaman->sisa_denda = $denda;
        $peminjaman->status_denda = $denda > 0 ? 'nunggak' : 'lunas';

        $peminjaman->save();

        $peminjaman->bwuku->increment('stok');

        return back()->with('success', 'Denda: Rp ' . number_format($denda));
    }

    // Contoh saat buku dikembalikan
    public function kembalikanBuku($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status == 'dipinjam') {
            $peminjaman->update([
                'status' => 'kembali',
                'tanggal_kembali' => now()
            ]);

            // TAMBAH STOK KEMBALI
            $buku = Buku::find($peminjaman->buku_id);
            $buku->increment('stok');
        }

        return back()->with('success', 'Buku kembali, stok bertambah.');
    }


    // File: RiwayatController.php atau PeminjamanController.php
    public function riwayat()
    {
        // Ambil SEMUA data peminjaman milik user ini
        $riwayat = Peminjaman::with('buku')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

            // dd($riwayat->pluck('status')->toArray());

        return view('anggota.riwayat', compact('riwayat'));
    }
}