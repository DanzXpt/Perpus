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

        // 1. AUTO UPDATE TERLAMBAT
        Peminjaman::where('status', 'dipinjam')
            ->whereDate('jatuh_tempo', '<', $today)
            ->each(function ($p) use ($today) {

                $hari = $today->diffInDays($p->jatuh_tempo);

                $denda = $hari * 1000;

                $p->update([
                    'status' => 'terlambat',
                    'denda' => $denda,
                    'sisa_denda' => $denda,
                    'status_denda' => 'nunggak',
                ]);
            });

        // 2. AMBIL DATA DASHBOARD
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'anggota')->count();
        $totalDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $totalTerlambat = Peminjaman::where('status', 'terlambat')->count();
        $totalDenda = Peminjaman::sum('denda');

        return view('kepala.dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'totalDipinjam',
            'totalTerlambat',
            'totalDenda'
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
        // (Jangan lupa buat file view/kepala/akun/view.blade.php nya nanti)
    }


    // Tambahkan fungsi ini di dalam class KepalaController

    public function edit($id)
    {
        // Cari data user berdasarkan ID
        $user = User::findOrFail($id);

        // Arahkan ke file view edit (pastikan filenya ada nanti)
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

        // password optional
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
            // Validasi opsional tergantung role
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
            // Data Dinamis (Kalau tidak ada di request, otomatis null di DB)
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'alamat' => $request->alamat,
            'nip' => $request->role == 'kepala' ? $request->nip_kepala : $request->nip_petugas,
            'no_telp' => $request->no_telp,
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
        $transaksi = Peminjaman::with(['user', 'buku'])
            ->latest()
            ->paginate(10); // Pakai paginate supaya kalau datanya banyak nggak kepanjangan

        return view('kepala.transaksi.index', compact('transaksi'));
    }
}