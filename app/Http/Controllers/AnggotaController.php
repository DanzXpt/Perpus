<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AnggotaController extends Controller
{
    /**
     * Tampilkan Dashboard Anggota
     */
    public function index()
    {
        $today = Carbon::today();
        $userId = Auth::id();

        // Update status ke terlambat jika melewati jatuh tempo secara otomatis
        $peminjamanTerlambat = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereDate('jatuh_tempo', '<', $today)
            ->get();

        foreach ($peminjamanTerlambat as $p) {
            $p->update([
                'status' => 'terlambat'
            ]);
        }

        // Statistik Peminjaman Pribadi
        $totalPinjam = Peminjaman::where('user_id', $userId)->count();

        // Menghitung jumlah peminjaman aktif untuk validasi kuota
        $jumlahPinjam = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
            ->count();

        $sedangDipinjam = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->count();

        // Konfigurasi Batas Pinjam
        $maxPinjam = 3;
        $sisaPinjam = max(0, $maxPinjam - $jumlahPinjam);
        $bolehPinjam = $jumlahPinjam < $maxPinjam;

        $terlambat = Peminjaman::where('user_id', $userId)
            ->where('status', 'terlambat')
            ->count();

        $totalDenda = Peminjaman::where('user_id', $userId)
            ->sum('sisa_denda');

        // Daftar peminjaman aktif untuk ditampilkan di tabel dashboard
        $peminjamanAktif = Peminjaman::with('buku')
            ->where('user_id', $userId)
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->latest()
            ->get();

        return view('anggota.dashboard', compact(
            'totalPinjam',
            'sedangDipinjam',
            'terlambat',
            'totalDenda',
            'peminjamanAktif',
            'sisaPinjam',
            'bolehPinjam'
        ));
    }

    /**
     * Tampilkan Daftar Buku dengan Fitur Search & Filter
     */
    public function buku(Request $request)
    {
        $query = Buku::with('kategori');

        // Pencarian berdasarkan Judul atau Penulis
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('penulis', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $buku = $query->latest()->paginate(10);
        $kategoris = Kategori::all();

        return view('anggota.buku', compact('buku', 'kategoris'));
    }

    /**
     * Tampilkan Pengajuan yang sedang menunggu persetujuan
     */
    public function pengajuan()
    {
        $pengajuan = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('anggota.pengajuan.index', compact('pengajuan'));
    }

    /**
     * Simpan Pengajuan Peminjaman Baru
     */
    public function store($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->stok < 1) {
            return back()->with('error', 'Stok buku habis.');
        }

        Peminjaman::create([
            'kode_transaksi' => 'TRX-' . time(),
            'user_id' => Auth::id(),
            'buku_id' => $buku->id,
            'tanggal_pinjam' => null,
            'jatuh_tempo' => null,
            'tanggal_kembali' => null,
            'status' => 'pending',
            'denda' => 0,
            'dibayar' => 0,
            'sisa_denda' => 0,
            'status_denda' => null
        ]);

        return back()->with('success', 'Pengajuan berhasil dikirim. Silakan tunggu konfirmasi petugas.');
    }

    /**
     * Tampilkan Seluruh Riwayat Transaksi Anggota
     */
    public function riwayat()
    {
        $riwayat = Peminjaman::with('buku')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $dendaPerHari = 1000;
        $today = Carbon::today();

        foreach ($riwayat as $r) {
            if ($r->jatuh_tempo) {
                $jatuhTempo = Carbon::parse($r->jatuh_tempo);
                $tanggalAkhir = ($r->status == 'kembali') ? Carbon::parse($r->tanggal_kembali) : $today;

                if ($tanggalAkhir->gt($jatuhTempo)) {
                    $hari = $jatuhTempo->diffInDays($tanggalAkhir);
                    $r->total_denda = $hari * $dendaPerHari;
                } else {
                    $r->total_denda = 0;
                }
            } else {
                $r->total_denda = 0;
            }

            $r->sudah_dibayar = ($r->denda ?? 0) - ($r->sisa_denda ?? 0);
            $r->sisa_denda_final = max(0, $r->total_denda - $r->sudah_dibayar);
        }

        return view('anggota.riwayat', compact('riwayat'));
    }

    /**
     * Tampilkan Daftar Denda yang Harus Dibayar
     */
    public function denda()
    {
        $riwayatDenda = Peminjaman::with('buku')
            ->where('user_id', auth()->id())
            ->where(function ($query) {
                $query->where('denda', '>', 0)
                    ->orWhere('status', 'dipinjam');
            })
            ->latest()
            ->get();

        $dendaPerHari = 1000;
        $today = Carbon::today();

        foreach ($riwayatDenda as $r) {
            $jatuhTempo = Carbon::parse($r->jatuh_tempo);
            $tanggalAkhir = ($r->status == 'kembali') ? Carbon::parse($r->tanggal_kembali) : $today;

            $dendaLive = ($tanggalAkhir->gt($jatuhTempo)) ? $jatuhTempo->diffInDays($tanggalAkhir) * $dendaPerHari : 0;
            $r->total_denda = max($dendaLive, ($r->denda ?? 0));
            $r->sudah_dibayar = max(0, ($r->denda ?? 0) - ($r->sisa_denda ?? 0));
            $r->sisa_denda_final = $r->total_denda - $r->sudah_dibayar;
        }

        // Hanya tampilkan data yang benar-benar memiliki sisa denda
        $riwayatDenda = $riwayatDenda->where('total_denda', '>', 0);

        return view('anggota.denda.index', compact('riwayatDenda'));
    }

    /**
     * Fitur Pengembalian Buku Mandiri
     */
    public function kembalikan($id)
    {
        $p = Peminjaman::findOrFail($id);
        $today = Carbon::today();
        $jatuhTempo = Carbon::parse($p->jatuh_tempo);

        $dendaPerHari = 1000;
        $denda = 0;

        if ($today->gt($jatuhTempo)) {
            $hari = $jatuhTempo->diffInDays($today);
            $denda = $hari * $dendaPerHari;
        }

        $p->update([
            'tanggal_kembali' => $today,
            'status' => 'kembali',
            'denda' => $denda,
            'sisa_denda' => $denda,
            'status_denda' => $denda > 0 ? 'nunggak' : 'lunas'
        ]);

        // Kembalikan stok buku
        $p->buku->increment('stok');

        return back()->with(
            'success',
            'Buku berhasil dikembalikan. Denda Anda: Rp ' . number_format($denda)
        );
    }
}