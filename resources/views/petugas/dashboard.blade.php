@extends('layouts.app')

@section('content')
    <div class="p-8 bg-slate-50 min-h-screen">

        {{-- Header Dashboard --}}
        {{-- <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-6">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tighter">Dashboard</h1>
                <p class="text-slate-500 text-sm font-medium mt-1">Selamat datang kembali, {{ Auth::user()->name }}!</p>
            </div>
            <div class="flex items-center gap-6">
                <div class="hidden md:flex flex-col items-end border-r border-slate-100 pr-6">
                    <span class="text-xs font-bold text-slate-400 block uppercase tracking-widest">Waktu Sistem</span>
                    <span
                        class="text-sm font-black text-slate-700">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</span>
                </div>
                <div
                    class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-black shadow-lg shadow-blue-100 text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </div> --}}

        {{-- Grid Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            {{-- Card: Total Buku --}}
            <div
                class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 group hover:border-blue-200 transition-all duration-300 hover:shadow-lg hover:shadow-blue-50/50 overflow-hidden">
                <div
                    class="w-14 h-14 bg-blue-50 text-blue-600 rounded-[1.2rem] flex items-center justify-center text-xl shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                    <i class="fas fa-book"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <h3 class="text-2xl font-black text-slate-800 leading-tight truncate">{{ $totalBuku }}</h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest truncate">Total Buku</p>
                </div>
            </div>

            {{-- Card: Anggota Aktif --}}
            <div
                class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 group hover:border-blue-200 transition-all duration-300 hover:shadow-lg hover:shadow-blue-50/50 overflow-hidden">
                <div
                    class="w-14 h-14 bg-blue-50 text-blue-600 rounded-[1.2rem] flex items-center justify-center text-xl shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                    <i class="fas fa-users"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <h3 class="text-2xl font-black text-slate-800 leading-tight truncate">{{ $totalAnggota }}</h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest truncate">Anggota Aktif</p>
                </div>
            </div>

            {{-- Card: Sedang Dipinjam --}}
            <div
                class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 group hover:border-emerald-200 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-50/50 overflow-hidden">
                <div
                    class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-[1.2rem] flex items-center justify-center text-xl shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-sm">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <h3 class="text-2xl font-black text-slate-800 leading-tight truncate">{{ $totalDipinjam }}</h3>
                    <p class="text-[9px] font-black text-emerald-600/70 uppercase tracking-widest truncate">Dipinjam</p>
                </div>
            </div>
            
            {{-- Terlambat --}}
            
            <div class="bg-white p-6 rounded-[2rem] border border-red-50 shadow-sm hover:shadow-md transition-all">
                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mb-4">
                    <i class="fas fa-clock"></i>
                </div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Terlambat</p>
                <h3 class="text-2xl font-black text-red-600">{{ $totalTerlambat }} <span
                        class="text-xs text-slate-400">Anggota</span></h3>
            </div>

            {{-- Card: Total Denda --}}
            <div
                class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-4 group hover:border-amber-200 transition-all duration-300 hover:shadow-lg hover:shadow-amber-50/50 overflow-hidden">
                <div
                    class="w-14 h-14 bg-amber-50 text-amber-600 rounded-[1.2rem] flex items-center justify-center text-xl shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-sm">
                    <i class="fas fa-sack-dollar"></i>
                </div>
                <div class="flex flex-col min-w-0">
                    <h3 class="text-lg font-black text-slate-800 leading-tight truncate">
                        Rp{{ number_format(abs($totalDenda), 0, ',', '.') }}
                    <p class="text-[9px] font-black text-amber-600/70 uppercase tracking-widest truncate">Total Denda</p>
                </div>
            </div>
        </div>

        {{-- Grid Bawah: Peminjaman Terbaru & Aksi Cepat --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            {{-- Kiri: Peminjaman Terbaru (8 Kolom) --}}
            <div
                class="lg:col-span-8 bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden group hover:border-blue-100 transition-all">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                    <h2 class="font-black text-slate-800 text-lg tracking-tight">Peminjaman Terbaru</h2>
                    <a href="{{ route('petugas.transaksi.index') }}"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-md shadow-blue-200">
                        Lihat Semua <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kode
                                </th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Anggota</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Buku
                                </th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($peminjamanTerbaru as $item)
                                <tr class="hover:bg-slate-50/50 transition-all">
                                    <td class="px-8 py-5 text-[10px] font-bold text-slate-400">#PJM-{{ $item->id }}</td>
                                    <td class="px-6 py-5 text-xs font-black text-slate-800 uppercase">
                                        {{ Str::limit($item->user->name, 15) }}</td>
                                    <td class="px-6 py-5 text-xs font-bold text-slate-700">
                                        <div class="max-w-[180px] truncate">{{ $item->buku->judul }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @php
                                            $statusClasses = match ($item->status) {
                                                'dipinjam' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'terlambat' => 'bg-red-50 text-red-600 border-red-100',
                                                default => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            };
                                        @endphp
                                        <span
                                            class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border {{ $statusClasses }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-16 text-center text-slate-400 text-sm font-medium italic">
                                        Belum ada data peminjaman terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kanan: Aksi Cepat (4 Kolom) --}}
            <div
                class="lg:col-span-4 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm group hover:border-blue-100 transition-all">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bolt text-lg"></i>
                    </div>
                    <h2 class="font-black text-slate-800 text-lg tracking-tight">Aksi Cepat</h2>
                </div>

                <div class="space-y-4">
                    <a href="{{ route('petugas.buku.create') }}"
                        class="flex items-center justify-between px-6 py-5 border-2 border-slate-100 text-slate-600 rounded-[1.5rem] hover:bg-slate-50 hover:border-slate-200 transition-all active:scale-95 group/btn">
                        <span class="text-[11px] font-black uppercase tracking-widest italic font-black">Tambah Buku</span>
                        <i class="fas fa-book-medical text-lg opacity-50 group-hover/btn:opacity-100"></i>
                    </a>

                    <a href="{{ route('petugas.transaksi.index') }}"
                        class="flex items-center justify-between px-6 py-5 bg-blue-600 text-white rounded-[1.5rem] hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 group/btn">
                        <span class="text-[11px] font-black uppercase tracking-widest italic">Transaksi Pinjam</span>
                        <i class="fa-solid fa-clipboard-list text-lg opacity-50 group-hover/btn:opacity-100"></i>
                    </a>

                    <a href="{{ route('petugas.pengajuan.index') }}"
                        class="flex items-center justify-between px-6 py-5 bg-emerald-500 text-white rounded-[1.5rem] hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-100 active:scale-95 group/btn">
                        <span class="text-[11px] font-black uppercase tracking-widest italic">Pengajuan</span>
                        <i class="fa-solid fa-bell-concierge text-lg opacity-50 group-hover/btn:opacity-100"></i>
                    </a>

                </div>

                <div class="mt-8 p-5 bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                    <p class="text-[10px] text-slate-400 font-bold uppercase leading-relaxed tracking-wider">
                        <i class="fas fa-info-circle text-blue-400 mr-1"></i> Dashboard otomatis memantau keterlambatan
                        buku.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection