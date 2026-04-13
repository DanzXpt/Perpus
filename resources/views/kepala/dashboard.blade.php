@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4">

        <div class="mb-8">
            <h2 class="text-2xl font-black text-slate-800">Laporan Kepala</h2>
            <p class="text-sm text-slate-500">Ringkasan performa perpustakaan digital hari ini.</p>
        </div>

        {{-- Grid diubah ke md:grid-cols-5 --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">

            {{-- 1. Judul Buku --}}
            <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-book"></i>
                </div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Judul Buku</p>
                <h3 class="text-xl font-black text-slate-800">{{ $totalBuku }}</h3>
            </div>

            {{-- 2. Total Stok (Fisik) --}}
            <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-layer-group"></i>
                </div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Stok Tersedia</p>
                <h3 class="text-xl font-black text-slate-800">{{ $totalStok }} <span class="text-[10px] text-slate-400 font-medium">Buku</span></h3>
            </div>

            {{-- 3. Total Pengguna (BARU) --}}
            <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Pengguna</p>
                <h3 class="text-xl font-black text-slate-800">{{ $totalUser }} <span class="text-[10px] text-slate-400 font-medium">Akun</span></h3>
            </div>

            {{-- 4. Terlambat --}}
            <div class="bg-white p-5 rounded-[2rem] border border-red-50 shadow-sm hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-clock"></i>
                </div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Terlambat</p>
                <h3 class="text-xl font-black text-red-600">{{ $totalTerlambat }} <span class="text-[10px] text-slate-400 font-medium">Orang</span></h3>
            </div>

            {{-- 5. Total Denda --}}
            <div class="bg-white p-5 rounded-[2rem] border border-amber-50 shadow-sm hover:shadow-md transition-all">
                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-wallet"></i>
                </div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Denda</p>
                <div class="text-lg font-black text-slate-800">
                    Rp{{ number_format($totalDenda, 0, ',', '.') }}
                </div>
            </div>

        </div>

        {{-- Banner Bawah --}}
        <div class="bg-[#0f172a] rounded-[2.5rem] p-10 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h4 class="text-xl font-bold mb-2">Sistem Monitoring Otomatis</h4>
                <p class="text-slate-400 text-sm max-w-md">Data diperbarui secara real-time berdasarkan aktivitas peminjaman.</p>
                <div class="mt-6">
                    <a href="{{ route('kepala.laporan.index') }}"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-xl font-bold text-xs transition-all inline-block">
                        Cetak Laporan Lengkap
                    </a>
                </div>
            </div>
            <i class="fas fa-chart-pie absolute -right-10 -bottom-10 text-[15rem] text-white/5"></i>
        </div>

    </div>
@endsection