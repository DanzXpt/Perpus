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
    public function index()
    {
        $today = Carbon::today();
        $userId = Auth::id();

        // 🔴 UPDATE STATUS TERLAMBAT
        $peminjamanTerlambat = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereDate('jatuh_tempo', '<', $today)
            ->get();

        foreach ($peminjamanTerlambat as $p) {
            $p->update([
                'status' => 'terlambat'
            ]);
        }

        // 🔴 TOTAL SEMUA RIWAYAT
        $totalPinjam = Peminjaman::where('user_id', $userId)->count();

        // 🔴 PINJAMAN AKTIF (INI YANG BENER)
        $jumlahPinjam = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
            ->count();

        // 🔴 KHUSUS YANG LAGI DIPEGANG
        $sedangDipinjam = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->count();

        $maxPinjam = 3;

        // 🔴 SISA KUOTA (biar gak minus kayak hidup)
        $sisaPinjam = max(0, $maxPinjam - $jumlahPinjam);

        // 🔴 BOLEH PINJAM ATAU ENGGAK
        $bolehPinjam = $jumlahPinjam < $maxPinjam;

        // 🔴 TERLAMBAT
        $terlambat = Peminjaman::where('user_id', $userId)
            ->where('status', 'terlambat')
            ->count();

        // 🔴 DENDA
        $totalDenda = Peminjaman::where('user_id', $userId)
            ->sum('sisa_denda');

        // 🔴 DATA AKTIF
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


    public function buku(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('penulis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $buku = $query->latest()->paginate(10);
        $kategoris = Kategori::all();

        return view('anggota.buku', compact('buku', 'kategoris'));
    }


    public function pengajuan()
    {
        $pengajuan = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('anggota.pengajuan.index', compact('pengajuan'));
    }


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

        return back()->with('success', 'Pengajuan berhasil, menunggu persetujuan');
    }


    public function riwayat()
    {
        // Hapus whereIn status agar semua keputusan (ditolak/menunggu) tetap muncul
        $riwayat = Peminjaman::with('buku')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $dendaPerHari = 1000;
        $today = \Carbon\Carbon::today();

        foreach ($riwayat as $r) {
            // Cek apakah kolom jatuh_tempo ada isinya (mencegah error pada status menunggu/ditolak)
            if ($r->jatuh_tempo) {
                $jatuhTempo = \Carbon\Carbon::parse($r->jatuh_tempo);

                // Tentukan tanggal pembanding
                $tanggalAkhir = ($r->status == 'kembali')
                    ? \Carbon\Carbon::parse($r->tanggal_kembali)
                    : $today;

                // Logika Hitung Denda Live
                if ($tanggalAkhir->gt($jatuhTempo)) {
                    $hari = $jatuhTempo->diffInDays($tanggalAkhir);
                    $r->total_denda = $hari * $dendaPerHari;
                } else {
                    $r->total_denda = 0;
                }
            } else {
                $r->total_denda = 0;
            }

            // Logika nominal pembayaran
            $r->sudah_dibayar = ($r->denda ?? 0) - ($r->sisa_denda ?? 0);
            $r->sisa_denda_final = max(0, $r->total_denda - $r->sudah_dibayar);
        }

        return view('anggota.riwayat', compact('riwayat'));
    }


    public function denda()
    {
        $riwayatDenda = Peminjaman::with('buku')
            ->where('user_id', auth()->id())
            ->where(function ($query) {
                $query->where('denda', '>', 0) // Denda yang sudah dicatat petugas
                    ->orWhere('status', 'dipinjam'); // Atau masih dipinjam (potensi denda)
            })
            ->latest()
            ->get();

        $dendaPerHari = 1000;
        $today = \Carbon\Carbon::today();

        foreach ($riwayatDenda as $r) {
            $jatuhTempo = \Carbon\Carbon::parse($r->jatuh_tempo);
            $tanggalAkhir = ($r->status == 'kembali') ? \Carbon\Carbon::parse($r->tanggal_kembali) : $today;

            // 1. Ambil Total Denda dari database
            // Jika di DB masih 0 tapi sudah telat, hitung Live. Jika di DB sudah ada, pakai yang di DB.
            $dendaLive = ($tanggalAkhir->gt($jatuhTempo)) ? $jatuhTempo->diffInDays($tanggalAkhir) * $dendaPerHari : 0;

            // Gunakan nilai terbesar antara hitungan live atau yang tercatat di DB
            $r->total_denda = max($dendaLive, ($r->denda ?? 0));

            // 2. Hitung yang sudah dibayar (Total di DB - Sisa di DB)
            $r->sudah_dibayar = ($r->denda ?? 0) - ($r->sisa_denda ?? 0);

            // Pastikan tidak minus
            if ($r->sudah_dibayar < 0)
                $r->sudah_dibayar = 0;

            // 3. Sisa Tagihan (Total Denda yang seharusnya - yang sudah dibayar)
            $r->sisa_denda_final = $r->total_denda - $r->sudah_dibayar;
        }

        // Filter lagi: Hanya tampilkan yang benar-benar ada dendanya
        $riwayatDenda = $riwayatDenda->where('total_denda', '>', 0);

        return view('anggota.denda.index', compact('riwayatDenda'));
    }


    public function kembalikan($id)
    {
        $p = Peminjaman::findOrFail($id);

        $today = Carbon::today();
        $jatuhTempo = Carbon::parse($p->jatuh_tempo);

        $dendaPerHari = 1000;
        $denda = 0;
        $statusDenda = 'lunas';

        if ($today->gt($jatuhTempo)) {

            $hari = $jatuhTempo->diffInDays($today);
            $denda = $hari * $dendaPerHari;
            $statusDenda = 'nunggak';
        }

        $p->update([
            'tanggal_kembali' => $today,
            'status' => 'kembali',
            'denda' => $denda,
            'sisa_denda' => $denda,
            'status_denda' => $denda > 0 ? 'nunggak' : 'lunas'
        ]);

        $p->buku->increment('stok');

        return back()->with(
            'success',
            'Buku dikembalikan, denda Rp ' . number_format($denda)
        );
    }


}