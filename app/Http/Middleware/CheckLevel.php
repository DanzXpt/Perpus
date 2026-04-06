<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
$user = \Illuminate\Support\Facades\Auth::user();
class Checkrole
{

    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Cek apakah user sudah login atau belum
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Ambil data user yang sedang login (Taro di DALAM sini biar gak merah)
        $user = Auth::user();

        // 3. Jika role user ada dalam daftar yang diizinkan, lanjut ke halaman tujuan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 4. Jika role GAK COCOK, lempar ke dashboard dengan pesan error
        return redirect('/dashboard')->with('error', 'Akses ditolak! role kamu adalah: ' . $user->role);
    }
}
