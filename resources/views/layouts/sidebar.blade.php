<div class="fixed left-0 top-0 h-full w-64 bg-slate-900 flex flex-col p-6 z-50 shadow-2xl">
    {{-- LOGO SECTION --}}
    <div class="flex items-center gap-3 px-2 mb-10">
        <div
            class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
            <i class="fa-solid fa-book-bookmark text-xl"></i>
        </div>
        <span class="font-black text-white text-xl tracking-tight uppercase italic">
            PERPUS<span class="text-indigo-500">ID</span>
        </span>
    </div>

    <div class="flex-1 space-y-2 overflow-y-auto">
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] px-4 mb-4">
            Menu Utama
        </p>

        @php
            if (Auth::user()->role == 'petugas') {
                $dashboardRoute = 'petugas.dashboard';
            } elseif (Auth::user()->role == 'anggota') {
                $dashboardRoute = 'anggota.dashboard';
            } else {
                $dashboardRoute = 'kepala.dashboard';
            }
        @endphp

        {{-- DASHBOARD --}}
        <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
            {{ request()->routeIs($dashboardRoute)
    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
    : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

            <i class="fa-solid fa-house text-lg"></i>
            <span class="font-black text-sm uppercase tracking-wide">
                Dashboard
            </span>
        </a>

        {{-- MENU KHUSUS KEPALA --}}
        @if(Auth::user()->role == 'kepala')

            <a href="{{ route('kepala.akun.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                        {{ request()->routeIs('kepala.akun.*')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-users text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">
                    Daftar User
                </span>
            </a>

            <a href="{{ route('petugas.buku.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                        {{ request()->routeIs('petugas.buku.*')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-box text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">
                    Kelola Buku
                </span>
            </a>

            <a href="{{ route('kepala.transaksi.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                        {{ request()->routeIs('kepala.transaksi.*')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-clipboard-list text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">
                    Transaksi
                </span>
            </a>

            <a href="{{ route('kepala.laporan.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                        {{ request()->routeIs('kepala.laporan.*')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-file-invoice text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">
                    Laporan
                </span>
            </a>

        @endif


        {{-- MENU KHUSUS PETUGAS --}}
        @if(Auth::user()->role == 'petugas')

            <a href="{{ route('petugas.buku.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                        {{ request()->routeIs('petugas.buku.*')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-box text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">
                    Kelola Buku
                </span>
            </a>

            <a href="{{ route('petugas.transaksi') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                        {{ request()->routeIs('petugas.transaksi')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-clipboard-list text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">
                    Transaksi Pinjam
                </span>
            </a>

            <a href="{{ route('petugas.pengajuan.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                        {{ request()->routeIs('petugas.pengajuan.*')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-bell-concierge text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">
                    Pengajuan
                </span>
            </a>

        @endif


        {{-- MENU KHUSUS ANGGOTA --}}
        @if(Auth::user()->role == 'anggota')

            <a href="{{ route('anggota.buku') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                        {{ request()->routeIs('anggota.buku')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-book-open text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">
                    Katalog Buku
                </span>
            </a>

            <a href="{{ route('anggota.riwayat') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                        {{ request()->routeIs('anggota.riwayat')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">
                    Riwayat Pinjam
                </span>
            </a>

            <a href="{{ route('anggota.pengajuan.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
                        {{ request()->routeIs('anggota.pengajuan.*')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                <i class="fa-solid fa-paper-plane text-lg"></i>
                <span class="font-black text-sm uppercase tracking-wide">
                    Pengajuan
                </span>
            </a>

        @endif
    </div>

    {{-- SECTION BAWAH --}}
    <div class="pt-6 border-t border-slate-800 space-y-1">

        <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 
            {{ request()->routeIs('profile.edit')
    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40'
    : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

            <i class="fa-solid fa-user-gear text-lg"></i>
            <span class="font-black text-sm uppercase tracking-wide">
                Profile Saya
            </span>
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-4 px-4 py-3.5 rounded-2xl text-rose-400 hover:bg-rose-500/10 transition-all font-black text-sm uppercase tracking-wide text-left border-none outline-none cursor-pointer">

                <i class="fa-solid fa-right-from-bracket text-lg"></i>
                <span>Log Out</span>
            </button>
        </form>

    </div>
</div>