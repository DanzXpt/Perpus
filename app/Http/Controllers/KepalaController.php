<?php

namespace App\Http\Controllers; // Pastikan ini baris paling atas setelah <?php

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Buku;

class KepalaController extends Controller
{
    public function index()
    {
        return view('kepala.dashboard');
    }

    public function users()
    {
        $users = User::all();
        return view('kepala.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        // Logika simpan user baru oleh kepala
    }

    public function destroyUser($id)
    {
        // Logika hapus user
    }

    public function laporan()
    {
        return view('kepala.laporan.index');
    }
}