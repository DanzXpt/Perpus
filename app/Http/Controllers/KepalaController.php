<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KepalaController extends Controller
{
    public function dashboard()
    {
        $today = now();

        // 1. UPDATE DATA TERLAMBAT & DENDA (Global)
        // Ambil yang statusnya masih dipinjam tapi sudah lewat jatuh tempo
        $peminjamanTerlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('jatuh_tempo', '<', $today)
            ->get();

        foreach ($peminjamanTerlambat as $p) {
            $hari = abs($today->diffInDays($p->jatuh_tempo, false));
            $denda = intval($hari) * 1000;

            $p->update([
                'status' => 'terlambat', // Tambahkan update status biar sinkron
                'denda' => $denda,
                'sisa_denda' => $denda,
                'status_denda' => 'nunggak'
            ]);
        }

        // 2. STATISTIK UTAMA
        $totalBuku = Buku::count();
        $totalStok = Buku::sum('stok');
        $totalUser = User::count();
        $totalAnggota = User::where('role', 'anggota')->count();

        // 3. STATISTIK TRANSAKSI (Untuk Monitoring Kepala)
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalTerlambat = Peminjaman::where('status', 'terlambat')->count();
        $totalDenda = Peminjaman::where('status_denda', 'nunggak')->sum('sisa_denda');

        // 4. AKTIVITAS TERBARU
        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->take(5)
            ->get();

        return view('kepala.dashboard', compact(
            'totalBuku',
            'totalStok',
            'totalUser',
            'totalAnggota',
            'totalDipinjam',
            'totalTerlambat',
            'totalDenda',
            'peminjamanTerbaru'
        ));
    }

    public function users()
    {
        $users = User::latest()->paginate(10);

        return view('kepala.akun.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        // Logika simpan user baru oleh kepala
    }

    public function destroyUser($id)
    {
        // Logika hapus user
    }

    // Tambahkan fungsi show
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('kepala.akun.view', compact('user'));
    }

    public function edit($id)
    {
        // Cari data user berdasarkan ID
        $user = User::findOrFail($id);

        return view('kepala.akun.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->only([
            'name',
            'email',
            'role',
            'nip_kepala',
            'nip_petugas'
        ]);

        // mapping nip
        if ($request->role == 'kepala') {
            $data['nip'] = $request->nip_kepala;
        } elseif ($request->role == 'petugas') {
            $data['nip'] = $request->nip_petugas;
        }

        // hapus field sementara
        unset($data['nip_kepala'], $data['nip_petugas']);

        // password opsional
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('kepala.akun.index')
            ->with('success', 'Data ' . $user->name . ' berhasil diupdate!');
    }
    public function create()
    {
        return view('kepala.akun.create');
    }
    public function store(Request $request)
    {
        // 1. Validasi Dasar + Input Dinamis
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:anggota,petugas,kepala',
            'nis' => 'required_if:role,anggota',
            'kelas' => 'required_if:role,anggota',
            'nip_petugas' => 'required_if:role,petugas',
            'nip_kepala' => 'required_if:role,kepala',
        ]);

        // 2. Simpan Data ke Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'alamat' => $request->alamat,
            'nip' => $request->role == 'kepala' ? $request->nip_kepala : $request->nip_petugas,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('kepala.akun.index')->with('success', 'Akun ' . $request->name . ' berhasil didaftarkan!');
    }

    public function laporan()
    {
        return view('kepala.laporan.index');
    }

    public function transaksi()
    {
        // Ambil data peminjaman, relasikan dengan user dan buku urutkan dari yang paling baru pinjam
        $transaksi = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(10); // Pakai paginate supaya kalau datanya banyak nggak kepanjangan

        return view('kepala.transaksi.index', compact('transaksi'));
    }
}