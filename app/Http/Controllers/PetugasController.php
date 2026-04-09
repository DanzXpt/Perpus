<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Peminjaman;
use Carbon\Carbon;


class PetugasController extends Controller
{
    /**
     * Dashboard Utama Petugas (Tampilan Statistik)
     */
    public function dashboard()
    {
        $today = Carbon::today();

        // ambil peminjaman yang sudah lewat tanggal kembali
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<', $today)
            ->get();

        // update status dan denda otomatis
        foreach ($peminjamanTerlambat as $p) {

            $hariTerlambat = Carbon::parse($p->tanggal_kembali)
                ->diffInDays($today);

            $denda = $hariTerlambat * 1000;

            $p->update([
                'status' => 'terlambat',
                'denda' => $denda
            ]);
        }

        // statistik setelah update
        $totalBuku = Buku::count();

        $totalAnggota = User::where('role', 'anggota')->count();

        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();

        $totalTerlambat = Peminjaman::where('status', 'terlambat')->count();

        $totalDenda = Peminjaman::sum('denda');

        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(5);

        return view('petugas.dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'totalDipinjam',
            'totalTerlambat',
            'totalDenda',
            'peminjamanTerbaru'
        ));
    }

    /**
     * Menampilkan daftar pengajuan peminjaman/pengembalian
     */
    public function transaksi()
    {
        $transaksi = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(10);

        return view('petugas.transaksi.index', compact('transaksi'));
    }

    public function daftarPengajuan()
    {
        // Ambil data yang statusnya masih 'pending'
        $pengajuan = Peminjaman::with(['user', 'buku'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(5);

        return view('petugas.pengajuan.index', compact('pengajuan'));
    }

    public function setujuiPengajuan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_pinjam' => now(),
            'tanggal_kembali' => now()->addDays(4)
        ]);

        // kurangi stok buku
        $peminjaman->buku->decrement('stok');

        return back()->with('success', 'Pengajuan berhasil disetujui');
    }

    public function tolakPengajuan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => 'ditolak'
        ]);

        return back()->with('success', 'Pengajuan ditolak');
    }

}