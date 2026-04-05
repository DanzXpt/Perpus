<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk DB Transaction

class PeminjamanController extends Controller
{
    /**
     * List Transaksi untuk Petugas
     */
    public function index()
    {
        $peminjaman = Peminjaman::with(['user', 'buku'])->latest()->paginate(10);
        return view('petugas.transaksi.index', compact('peminjaman'));
    }

    /**
     * Proses Anggota Mengajukan Pinjam
     */
    public function ajukanPinjam(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        // KUNCI UTAMA: Cek apakah stok lebih besar dari 0
        if ($buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku "' . $buku->judul . '" sudah habis!');
        }

        // Cek limit pinjaman user (Maksimal 3)
        $cekPinjaman = Peminjaman::where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->count();

        if ($cekPinjaman >= 3) {
            return back()->with('error', 'Kamu sudah mencapai batas maksimal (3 buku)!');
        }

        \DB::transaction(function () use ($buku) {
            // Simpan transaksi
            Peminjaman::create([
                'user_id' => auth()->id(),
                'buku_id' => $buku->id,
                'tanggal_pinjam' => now(),
                'tanggal_kembali' => now()->addDays(7),
                'status' => 'dipinjam',
            ]);

            // Kurangi stok hanya jika pengecekan di atas lolos
            $buku->decrement('stok');
        });

        return redirect()->route('anggota.dashboard')->with('success', 'Buku berhasil dipinjam!');
    }

    /**
     * Batalkan Pinjaman
     */
    public function batalPinjam($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        // Kembalikan stok
        $pinjam->buku->increment('stok');
        $pinjam->delete();

        return back()->with('success', 'Peminjaman dibatalkan.');
    }

    public function kembalikanBuku($id)
    {
        $pinjam = Peminjaman::findOrFail($id);

        // 1. Cek Denda (Misal: 1000 per hari jika lewat tanggal kembali)
        $tglKembali = \Carbon\Carbon::parse($pinjam->tanggal_kembali);
        $hariIni = \Carbon\Carbon::today();
        $denda = 0;

        if ($hariIni->gt($tglKembali)) {
            $selisihHari = $hariIni->diffInDays($tglKembali);
            $denda = $selisihHari * 1000; // Rp 1.000 per hari
        }

        // 2. Update Data Peminjaman
        $pinjam->update([
            'status' => 'kembali',
            'tanggal_pengembalian' => $hariIni, // Pastikan ada kolom ini di migrasi
            'denda' => $denda
        ]);

        // 3. Kembalikan Stok Buku
        $pinjam->buku->increment('stok');

        return back()->with('success', 'Buku berhasil dikembalikan! ' . ($denda > 0 ? 'Denda: Rp ' . number_format($denda) : ''));
    }
}