@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="space-y-8">

        {{-- Banner Sambutan --}}
        <div class="relative overflow-hidden bg-indigo-600 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-indigo-200">
            <div class="relative z-10">
                <h2 class="text-3xl font-black mb-2 flex items-center gap-3">
                    Halo, {{ Auth::user()->name }}! <span class="animate-bounce">👋</span>
                </h2>
                <p class="text-indigo-100 opacity-90 max-w-md font-medium">
                    @if($sedangDipinjam > 0)
                        <i class="fa-solid fa-circle-info mr-1"></i> Kamu memiliki {{ $sedangDipinjam }} buku yang sedang
                        dibaca. Jangan lupa dikembalikan tepat waktu ya!
                    @else
                        <i class="fa-solid fa-face-smile mr-1"></i> Kamu tidak memiliki pinjaman aktif. Yuk, cari buku menarik
                        di katalog!
                    @endif
                </p>
            </div>
            {{-- Ikon Background Besar --}}
            <i class="fa-solid fa-book-bookmark absolute right-10 -bottom-5 text-[12rem] opacity-10 rotate-12"></i>
        </div>

        {{-- Grid Statistik dengan Ikon --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Total Pinjam --}}
            <div
                class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm group hover:border-indigo-200 transition-all relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-slate-400 text-[10px] font-black uppercase mb-2 tracking-widest">Total Pinjam</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $totalPinjam }}</h3>
                </div>
                <i
                    class="fa-solid fa-layer-group absolute -right-2 -bottom-2 text-5xl text-slate-50 group-hover:text-indigo-50 transition-colors"></i>
            </div>

            {{-- Sedang Dipinjam --}}
            <div
                class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm border-l-4 border-l-amber-400 group hover:bg-amber-50/30 transition-all relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-slate-400 text-[10px] font-black uppercase mb-2 text-amber-600 tracking-widest">Sedang
                        Dipinjam</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $sedangDipinjam }}</h3>
                </div>
                <i
                    class="fa-solid fa-book-open-reader absolute -right-2 -bottom-2 text-5xl text-amber-100/50 group-hover:text-amber-200/50 transition-colors"></i>
            </div>

            {{-- Terlambat --}}
            <div
                class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm border-l-4 border-l-rose-500 group hover:bg-rose-50/30 transition-all relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-slate-400 text-[10px] font-black uppercase mb-2 text-rose-600 tracking-widest">Terlambat
                    </p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $terlambat }}</h3>
                </div>
                <i
                    class="fa-solid fa-clock-rotate-left absolute -right-2 -bottom-2 text-5xl text-rose-100/50 group-hover:text-rose-200/50 transition-colors"></i>
            </div>

            {{-- Total Denda --}}
            <div
                class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm group hover:border-emerald-200 transition-all relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-slate-400 text-[10px] font-black uppercase mb-2 text-emerald-600 tracking-widest">Total
                        Denda</p>
                    <h3 class="text-xl font-black text-emerald-600 italic">Rp {{ number_format($totalDenda, 0, ',', '.') }}
                    </h3>
                </div>
                <i
                    class="fa-solid fa-wallet absolute -right-2 -bottom-2 text-5xl text-emerald-50 group-hover:text-emerald-100 transition-colors"></i>
            </div>
        </div>

        {{-- Tabel Pinjaman Aktif --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                <h3 class="font-black text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-indigo-600"></i> Pinjaman Aktif
                </h3>
                <a href="{{ route('anggota.buku') }}"
                    class="text-indigo-600 text-xs font-black uppercase tracking-widest hover:text-indigo-800 transition-all">
                    Cari Buku Lagi <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="p-4">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase font-black tracking-[0.1em]">
                            <th class="p-4"><i class="fa-solid fa-book mr-2"></i>Buku</th>
                            <th class="p-4 text-center"><i class="fa-solid fa-calendar-day mr-2"></i>Batas Kembali</th>
                            <th class="p-4 text-center"><i class="fa-solid fa-shield-halved mr-2"></i>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamanAktif as $item)
                            <tr class="bg-slate-50/50 hover:bg-slate-100/80 transition-all group">
                                <td class="p-4 rounded-l-2xl">
                                    <div class="font-bold text-sm text-slate-700 group-hover:text-indigo-600 transition-colors">
                                        {{ $item->buku->judul }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">Dipinjam:
                                        {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</div>
                                </td>
                                <td
                                    class="p-4 text-center text-sm font-black {{ \Carbon\Carbon::parse($item->tanggal_kembali)->isPast() ? 'text-rose-500' : 'text-slate-600' }}">
                                    {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                                </td>
                                <td class="p-4 text-center rounded-r-2xl">
                                    @if(\Carbon\Carbon::parse($item->tanggal_kembali)->isPast())
                                        <span
                                            class="bg-rose-100 text-rose-600 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase border border-rose-200">
                                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> Terlambat
                                        </span>
                                    @else
                                        <span
                                            class="bg-amber-100 text-amber-600 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase border border-amber-200">
                                            <i class="fa-solid fa-hourglass-half mr-1"></i> Dipinjam
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-16">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-book-open text-4xl mb-3"></i>
                                        <p class="text-sm font-bold italic">Kamu sedang tidak meminjam buku.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection