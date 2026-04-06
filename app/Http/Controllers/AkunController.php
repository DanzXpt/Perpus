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
            $rules['nip'] = 'nullable|unique:petugas,nip';
        }

        if ($request->role == 'kepala') {
            $rules['nip'] = 'nullable|unique:kepala_perpus,nip';
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

            if ($request->role == 'anggota') {

                Anggota::create([
                    'user_id' => $user->id,
                    'nis' => $request->nis,
                    'kelas' => $request->kelas,
                    'alamat' => $request->alamat,
                ]);
            }

            if ($request->role == 'petugas') {

                Petugas::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                ]);
            }

            if ($request->role == 'kepala') {

                KepalaPerpus::create([
                    'user_id' => $user->id,
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
    // UPDATE
    // ===============================
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($user->role == 'anggota') {
            $rules['nis'] = 'required|unique:anggota,nis,' . ($user->anggota->id ?? 0);
            $rules['kelas'] = 'required';
        }

        if ($user->role == 'petugas') {
            $rules['nip'] = 'nullable|unique:petugas,nip,' . ($user->petugas->id ?? 0);
        }

        if ($user->role == 'kepala') {
            $rules['nip'] = 'nullable|unique:kepala_perpus,nip,' . ($user->kepala->id ?? 0);
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($user->role == 'anggota') {

                Anggota::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nis' => $request->nis,
                        'kelas' => $request->kelas,
                        'alamat' => $request->alamat,
                    ]
                );
            }

            if ($user->role == 'petugas') {

                Petugas::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $request->nip,
                        'no_telp' => $request->no_hp,
                        'alamat' => $request->alamat,
                    ]
                );
            }

            if ($user->role == 'kepala') {

                KepalaPerpus::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nip' => $request->nip,
                    ]
                );
            }

            DB::commit();

            return redirect()
                ->route('kepala.akun.index')
                ->with('success', 'Akun berhasil diupdate');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withErrors('Gagal update: ' . $e->getMessage());
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