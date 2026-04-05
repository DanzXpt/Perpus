<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLevel
{
    public function handle(Request $request, Closure $next, ...$levels)
    {
        // 1. Cek apakah user sudah login (pakai guard default 'web')
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Ambil data user yang sedang login
        $user = auth()->user();

        // 3. Cek apakah level user ada di dalam daftar level yang diizinkan
        // Contoh: @middleware('checkLevel:petugas,admin')
        if (in_array($user->level, $levels)) {
            return $next($request);
        }

        // 4. Jika tidak punya akses, lempar balik sesuai levelnya
        if ($user->level == 'anggota') {
            return redirect()->route('anggota.dashboard')->with('error', 'Kamu tidak punya akses ke halaman Petugas!');
        }

        return redirect('/')->with('error', 'Akses ditolak.');
    }
}