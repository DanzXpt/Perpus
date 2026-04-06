<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    // anggota mengajukan pinjam
    public function store(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis');
        }

        // cek sudah pernah pending
        $cek = Peminjaman::where('user_id', Auth::id())
            ->where('buku_id', $buku->id)
            ->where('status', 'pending')
            ->exists();

        if ($cek) {
            return back()->with('error', 'Kamu sudah mengajukan buku ini');
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'buku_id' => $buku->id,
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(7),
            'status' => 'pending'
        ]);

        return redirect()->route('anggota.pengajuan.index')
            ->with('success', 'Pengajuan berhasil dikirim');
    }

    // petugas setujui
    public function setujui($id)
    {
        $pinjam = Peminjaman::findOrFail($id);
        $buku = $pinjam->buku;

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis');
        }

        $pinjam->update([
            'status' => 'dipinjam'
        ]);

        $buku->decrement('stok');

        return back()->with('success', 'Pengajuan disetujui');
    }

    // petugas tolak
    public function tolak($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $pinjam->update([
            'status' => 'ditolak'
        ]);

        return back()->with('success', 'Pengajuan ditolak');
    }

    // pengembalian
    public function kembali($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        $pinjam->update([
            'status' => 'kembali'
        ]);

        $pinjam->buku->increment('stok');

        return back()->with('success', 'Buku dikembalikan');
    }
}