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
        $userId = Auth::id();

        $totalPinjam = Peminjaman::where('user_id', $userId)->count();

        $sedangDipinjam = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->count();

        $terlambat = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::today())
            ->count();

        $totalDenda = Peminjaman::where('user_id', $userId)->sum('denda');

        $peminjamanAktif = Peminjaman::with('buku')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->get();

        return view('anggota.dashboard', compact(
            'totalPinjam',
            'sedangDipinjam',
            'terlambat',
            'totalDenda',
            'peminjamanAktif'
        ));
    }

    public function buku(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $buku = $query->paginate(10);
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

    public function riwayat()
    {
        $riwayat = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['dipinjam', 'kembali'])
            ->latest()
            ->get();

        return view('anggota.riwayat', compact('riwayat'));
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => 'kembali',
            'tanggal_pengembalian' => now()
        ]);

        $peminjaman->buku->increment('stok');

        return back()->with('success', 'Buku berhasil dikembalikan');
    }
}