<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class KepalaController extends Controller
{
    public function index()
    {
        // 1. Ambil data dasar
        $totalJudulBuku = \App\Models\Buku::count();
        $totalStokBuku = \App\Models\Buku::sum('stok');
        $totalAnggota = \App\Models\User::where('role', 'anggota')->count();
        $totalPinjam = \App\Models\Transaksi::where('status', 'dipinjam')->count();

        // 2. HITUNG BUKU TERLAMBAT (Ini yang bikin error di baris 35 tadi)
        // Logikanya: Status masih 'dipinjam' DAN tanggal kembali seharusnya sudah lewat dari hari ini
        $terlambat = \App\Models\Transaksi::where('status', 'dipinjam')
            ->where('tgl_kembali', '<', now()) // Sesuaikan nama kolom tanggal matimu (tgl_kembali / tgl_batas)
            ->count();

        // 3. Tembak 0 buat denda (biar aman dari error Column Not Found)
        $totalDenda = 0;

        // 4. KIRIM SEMUANYA KE VIEW (WAJIB MASUK COMPACT SEMUA!)
        return view('kepala.dashboard', compact(
            'totalJudulBuku',
            'totalStokBuku',
            'totalAnggota',
            'totalPinjam',
            'terlambat',
            'totalDenda'
        ));
    }

    public function users()
    {
        $users = User::latest()->paginate(10);

        // Alamat baru: folder kepala -> folder akun -> file file
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
        $user = \App\Models\User::findOrFail($id);
        return view('kepala.akun.view', compact('user'));
        // (Jangan lupa buat file view/kepala/akun/view.blade.php nya nanti)
    }


    // Tambahkan fungsi ini di dalam class KepalaController

    public function edit($id)
    {
        // Cari data user berdasarkan ID
        $user = \App\Models\User::findOrFail($id);

        // Arahkan ke file view edit (pastikan filenya ada nanti)
        return view('kepala.akun.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Logika simpan perubahan data user
        $user = \App\Models\User::findOrFail($id);
        $user->update($request->all());

        return redirect()->route('kepala.akun.index')->with('success', 'Data berhasil diupdate!');
    }

    // Tambahkan ini di dalam class KepalaController

    public function create()
    {
        // Arahkan ke file view form tambah
        // Pastikan kamu sudah buat filenya di resources/views/kepala/akun/create.blade.php
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
            // Validasi opsional tergantung role
            'nis' => 'required_if:role,anggota',
            'kelas' => 'required_if:role,anggota',
            'nip_petugas' => 'required_if:role,petugas',
            'nip_kepala' => 'required_if:role,kepala',
        ]);

        // 2. Simpan Data ke Database
        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            // Data Dinamis (Kalau tidak ada di request, otomatis null di DB)
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
        // Ambil data peminjaman, relasikan dengan user dan buku
        // Kita urutkan dari yang paling baru pinjam
        $transaksi = \App\Models\Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(10); // Pakai paginate supaya kalau datanya banyak nggak kepanjangan

        return view('kepala.transaksi.index', compact('transaksi'));
    }

}