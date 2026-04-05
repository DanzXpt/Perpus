<div class="fixed left-0 top-0 h-full w-64 bg-slate-900 flex flex-col p-6 z-50 shadow-2xl">
    {{-- LOGO SECTION --}}
    <div class="flex items-center gap-3 px-2 mb-10">
        <div
            class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
            <i class="fa-solid fa-book-bookmark text-xl"></i>
        </div>
        <span class="font-black text-white text-xl tracking-tight uppercase italic">PERPUS<span
                class="text-indigo-500">ID</span></span>
    </div>

    <div class="flex-1 space-y-2">
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-4 mb-4">Menu Utama</p>

        {{-- DASHBOARD DINAMIS --}}
        @php
            $dashboardRoute = Auth::user()->level == 'petugas' ? 'petugas.dashboard' : 'anggota.dashboard';
        @endphp

        <a href="{{ route($dashboardRoute) }}"
            class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('*.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fa-solid fa-house text-lg"></i>
            <span class="font-black text-sm uppercase tracking-wide">Dashboard</span>
        </a>

        {{-- MENU KHUSUS PETUGAS --}}
        @if(Auth::user()->level == 'petugas')
            <a href="{{ route('petugas.buku.index') }}"
                class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('petugas.buku.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-box text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">Kelola Buku</span>
            </a>

            <a href="{{ route('petugas.transaksi') }}"
                class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('petugas.transaksi') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-clipboard-list text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">Transaksi Pinjam</span>
            </a>
        @endif

        {{-- MENU KHUSUS ANGGOTA --}}
        @if(Auth::user()->level == 'anggota')
            <a href="{{ route('anggota.buku') }}"
                class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('anggota.buku') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-book-open text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">Katalog Buku</span>
            </a>

            <a href="{{ route('anggota.riwayat') }}"
                class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('anggota.riwayat') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">Riwayat Pinjam</span>
            </a>
        @endif
    </div>

    {{-- SECTION BAWAH (PROFIL & LOGOUT) --}}
    <div class="pt-6 border-t border-slate-800 space-y-1">

        {{-- 1. LOGIKA DEFINISI VARIABEL (HARUS DI ATAS) --}}
        @php
            if (Auth::user()->level == 'kepala') {
                $profileRoute = 'kepala.profile';
            } elseif (Auth::user()->level == 'petugas') {
                $profileRoute = 'petugas.profile';
            } else {
                $profileRoute = 'anggota.profile';
            }
        @endphp

        {{-- 2. PANGGIL VARIABELNYA --}}
        <a href="{{ route($profileRoute) }}"
            class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('*.profile') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <i class="fa-solid fa-user-gear text-lg"></i>
            <span class="font-black text-sm uppercase tracking-wide">Profil Saya</span>
        </a>

        {{-- TOMBOL LOGOUT --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl text-rose-400 hover:bg-rose-500/10 transition-all font-black text-sm uppercase tracking-wide text-left border-none outline-none">
                <i class="fa-solid fa-right-from-bracket text-lg"></i>
                <span>Keluar Akun</span>
            </button>
        </form>
    </div>
</div>