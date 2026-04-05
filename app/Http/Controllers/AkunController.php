<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Anggota;
use App\Models\Petugas;
use App\Models\kepala_perpus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    // --- FITUR AUTENTIKASI (LOGIN/LOGOUT) ---

    // Menampilkan Form Login (Gunakan ini di rute /login)
    public function loginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login'); 
    }

    // Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->with('error', 'Email atau Password salah!');
    }

    // --- FITUR MANAGEMENT AKUN (KHUSUS ADMIN/KEPALA) ---

    // Menampilkan Daftar Semua Akun
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('akun.index', compact('users'));
    }

    // Form Tambah Akun Baru
    public function create()
    {
        return view('akun.create');
    }

    // Simpan Akun Baru ke Database
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'level' => 'required|in:anggota,petugas,kepala',
        ];

        // Validasi tambahan berdasarkan level
        if ($request->level == 'anggota') {
            $rules['nis'] = 'required|unique:anggota,nis';
            $rules['kelas'] = 'required';
        } elseif ($request->level == 'petugas') {
            $rules['nip_petugas'] = 'nullable|unique:petugas,nip_petugas';
        } elseif ($request->level == 'kepala') {
            $rules['nip_kepala'] = 'nullable|unique:kepala_perpus,nip_kepala';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'level' => $request->level,
            ]);

            // Simpan ke tabel detail sesuai level
            if ($request->level == 'anggota') {
                Anggota::create([
                    'user_id' => $user->id,
                    'nis' => $request->nis,
                    'kelas' => $request->kelas,
                    'alamat' => $request->alamat,
                ]);
            } elseif ($request->level == 'petugas') {
                Petugas::create([
                    'user_id' => $user->id,
                    'nip_petugas' => $request->nip_petugas,
                    'no_hp' => $request->no_hp,
                ]);
            } elseif ($request->level == 'kepala') {
                kepala_perpus::create([
                    'user_id' => $user->id,
                    'nip_kepala' => $request->nip_kepala,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.akun.index')->with('success', 'Akun ' . $request->level . ' berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan akun: ' . $e->getMessage()]);
        }
    }

    // Form Edit Akun
    public function edit($id)
    {
        $user = User::with(['anggota', 'petugas', 'kepala'])->findOrFail($id);
        return view('akun.edit', compact('user'));
    }

    // Update Data Akun
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($user->level == 'anggota') {
            $rules['nis'] = 'required|unique:anggota,nis,' . ($user->anggota?->id ?? 'NULL');
            $rules['kelas'] = 'required';
        } elseif ($user->level == 'petugas') {
            $rules['nip_petugas'] = 'nullable|unique:petugas,nip_petugas,' . ($user->petugas?->id ?? 'NULL');
        } elseif ($user->level == 'kepala') {
            $rules['nip_kepala'] = 'nullable|unique:kepala_perpus,nip_kepala,' . ($user->kepala?->id ?? 'NULL');
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($user->level == 'anggota') {
                $user->anggota()->updateOrCreate(['user_id' => $user->id], [
                    'nis' => $request->nis, 
                    'kelas' => $request->kelas, 
                    'alamat' => $request->alamat
                ]);
            } elseif ($user->level == 'petugas') {
                $user->petugas()->updateOrCreate(['user_id' => $user->id], [
                    'nip_petugas' => $request->nip_petugas, 
                    'no_hp' => $request->no_hp
                ]);
            } elseif ($user->level == 'kepala') {
                $user->kepala()->updateOrCreate(['user_id' => $user->id], [
                    'nip_kepala' => $request->nip_kepala
                ]);
            }

            DB::commit();
            return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal update akun: ' . $e->getMessage()]);
        }
    }

    // Hapus Akun
    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil dihapus!');
    }
}