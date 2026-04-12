<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Anggota;
use App\Models\Petugas;
use App\Models\KepalaPerpus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    // ===============================
    // LIST AKUN
    // ===============================
    public function index()
    {
        $users = User::with(['anggota', 'petugas', 'kepala'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kepala.akun.index', compact('users'));
    }

    // ===============================
    // FORM CREATE
    // ===============================
    public function create()
    {
        return view('kepala.akun.create');
    }

    // ===============================
    // STORE
    // ===============================
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:anggota,petugas,kepala',
        ];

        if ($request->role == 'anggota') {
            $rules['nis'] = 'required|unique:anggota,nis';
            $rules['kelas'] = 'required';
        }

        if ($request->role == 'petugas') {
            $rules['nip'] = 'required|unique:petugas,nip';
            $rules['no_hp'] = 'required|regex:/^(08|\\+62)[0-9]{8,13}$/';
        }

        if ($request->role == 'kepala') {
            $rules['nip'] = 'required|unique:kepala_perpus,nip';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            if ($user->role == 'anggota') {
                $user->anggota()->update([
                    'nis' => $request->nis,
                    'kelas' => $request->kelas,
                ]);
            }

            if ($user->role == 'petugas') {

                $noHp = $request->no_hp;

                if (str_starts_with($noHp, '08')) {
                    $noHp = preg_replace('/^0/', '+62', $noHp);
                }

                $user->petugas()->update([
                    'nip' => $request->nip,
                    'no_hp' => $noHp,
                ]);
            }

            if ($user->role == 'kepala') {
                $user->kepala()->update([
                    'nip' => $request->nip,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('kepala.akun.index')
                ->with('success', 'Akun berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors('Gagal simpan akun: ' . $e->getMessage());
        }
    }

    // ===============================
    // EDIT
    // ===============================
    public function edit($id)
    {
        $user = User::with(['anggota', 'petugas', 'kepala'])
            ->findOrFail($id);

        return view('kepala.akun.edit', compact('user'));
    }

    // ===============================
    // UPDATE (FIXED LOGIC)
    // ===============================
    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);

        // Validasi Email agar tidak bentrok dengan diri sendiri
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        // Validasi tambahan berdasarkan role
        if ($user->role == 'anggota') {
            $rules['nis'] = 'required|unique:anggota,nis,' . ($user->anggota->id ?? 'null');
            $rules['kelas'] = 'required';
        } elseif ($user->role == 'petugas') {
            $rules['nip'] = 'nullable|unique:petugas,nip,' . optional($user->petugas)->id;
        } elseif ($user->role == 'kepala') {
            $rules['nip'] = 'nullable|unique:kepala_perpus,nip,' . ($user->kepala->id ?? 'null');
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            // 1. Update Tabel Users
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // 2. Update Password jika diisi
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            // 3. Update Tabel Relasi (Gunakan updateOrCreate untuk keamanan data)
            if ($user->role == 'anggota') {
                $user->anggota()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nis' => $request->nis,
                        'kelas' => $request->kelas,
                    ]
                );
            } elseif ($user->role == 'petugas') {
                $user->petugas()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $request->nip,
                        'no_hp' => $request->no_hp,
                    ]
                );
            } elseif ($user->role == 'kepala') {
                $user->kepala()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $request->nip,
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('kepala.akun.index')
                ->with('success', 'Akun berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors('Gagal update akun: ' . $e->getMessage());
        }
    }

    // ===============================
    // DELETE
    // ===============================
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            // Hapus detail di tabel relasi terlebih dahulu
            Anggota::where('user_id', $id)->delete();
            Petugas::where('user_id', $id)->delete();
            KepalaPerpus::where('user_id', $id)->delete();

            $user->delete();

            DB::commit();

            return redirect()
                ->route('kepala.akun.index')
                ->with('success', 'Akun berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors('Gagal hapus: ' . $e->getMessage());
        }
    }

    // ===============================
    // SHOW DETAIL
    // ===============================
    public function show($id)
    {
        $user = User::with(['anggota', 'petugas', 'kepala'])
            ->findOrFail($id);

        return view('kepala.akun.view', compact('user'));
    }
}