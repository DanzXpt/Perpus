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
        $today = now();

        // 🔥 update denda otomatis
        $peminjaman = Peminjaman::where('status', 'dipinjam')
            ->whereDate('jatuh_tempo', '<', $today)
            ->get();

        foreach ($peminjaman as $p) {

            $hari = $today->diffInDays($p->jatuh_tempo);
            $denda = $hari * 1000;

            $p->update([
                'status' => 'terlambat',
                'denda' => $denda,
                'sisa_denda' => $denda,
                'status_denda' => 'nunggak'
            ]);
        }

        // statistik
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'anggota')->count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalTerlambat = Peminjaman::where('status', 'terlambat')->count();
        $totalDenda = Peminjaman::sum('denda');

        $peminjamanTerbaru = Peminjaman::with('user', 'buku')
            ->latest()
            ->take(5)
            ->get();

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

        // Pastikan stok berkurang HANYA jika status sebelumnya pending
        if ($peminjaman->status == 'pending') {
            $peminjaman->update(['status' => 'dipinjam']);

            // AMBIL MODEL BUKUNYA
            $buku = Buku::find($peminjaman->buku_id);

            // KURANGI STOK (Perintah ini langsung simpan ke DB)
            $buku->decrement('stok');
        }

        return back()->with('success', 'Berhasil disetujui, stok berkurang.');
    }
    public function tolakPengajuan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => 'ditolak'
        ]);

        return redirect()->back()->with('success', 'Pengajuan peminjaman telah ditolak.');
    }
}