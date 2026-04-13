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
        $userId = auth()->id();

        // 1. CEK DENDA (Prioritas Utama)
        // Pastikan kolom 'sisa_denda' di DB tipenya integer/decimal dan default 0
        $punyaDenda = Peminjaman::where('user_id', $userId)
            ->where('sisa_denda', '>', 0)
            ->exists();

        if ($punyaDenda) {
            return back()->with('error', 'Gagal! Kamu masih memiliki denda yang belum dibayar.');
        }

        $buku = Buku::findOrFail($id);

        // 2. CEK STOK
        if ($buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        // 3. HITUNG PINJAMAN AKTIF (Limit 3)
        $jumlahPinjam = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
            ->count();

        if ($jumlahPinjam >= 3) {
            return back()->with('error', 'Limit tercapai! Selesaikan pinjaman sebelumnya (max 3 buku).');
        }

        // 4. CEK DUPLIKAT
        $sudahAda = Peminjaman::where('user_id', $userId)
            ->where('buku_id', $id)
            ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
            ->exists();

        if ($sudahAda) {
            return back()->with('error', 'Kamu sudah meminjam buku ini atau sedang dalam antrean.');
        }

        try {
            // Generate Kode Transaksi yang unik
            $kodeTransaksi = 'TRX-' . now()->format('YmdHis') . rand(100, 999);

            Peminjaman::create([
                'kode_transaksi' => $kodeTransaksi,
                'user_id' => $userId,
                'buku_id' => $id,
                'tanggal_pinjam' => now(),
                'jatuh_tempo' => now()->addDays(4), // Pinjam 4 hari
                'status' => 'pending',
                'denda' => 0,
                'dibayar' => 0,
                'sisa_denda' => 0,
                'status_denda' => 'lunas',
            ]);

            return back()->with('success', 'Pengajuan berhasil dikirim! Silahkan hubungi petugas.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses data: ' . $e->getMessage());
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

        $peminjaman->buku->increment('stok');

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