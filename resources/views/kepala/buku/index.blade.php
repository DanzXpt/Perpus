@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 pb-20">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Daftar Koleksi Buku</h2>
                <p class="text-sm text-gray-500 mt-1">Laporan lengkap seluruh koleksi pustaka digital.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('kepala.laporan.buku') }}"
                    class="px-6 py-3 bg-emerald-600 text-white rounded-2xl font-bold shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all flex items-center gap-2">
                    <i class="fas fa-file-export"></i> Cetak Laporan
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Judul</p>
                <h4 class="text-2xl font-black text-slate-800 mt-1">{{ $buku->count() }} Judul</h4>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Eksemplar</p>
                <h4 class="text-2xl font-black text-slate-800 mt-1">{{ $buku->sum('stok') }} Buku</h4>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori</p>
                <h4 class="text-2xl font-black text-slate-800 mt-1">Dinamis</h4>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Info Buku
                            </th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Penerbit
                                & Tahun</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Stok</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($buku as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-16 bg-slate-100 rounded-lg flex-shrink-0 overflow-hidden shadow-sm">
                                            @if($item->cover)
                                                <img src="{{ asset('storage/' . $item->cover) }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                    <i class="fas fa-book text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-800 group-hover:text-indigo-600 transition-colors">
                                                {{ $item->judul }}
                                            </p>
                                            <p class="text-xs text-slate-400 font-medium mt-0.5">Penulis: {{ $item->penulis }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <p class="text-sm font-bold text-slate-700">{{ $item->penerbit }}</p>
                                    <p class="text-xs text-slate-400 mt-1 italic">{{ $item->tahun_terbit }}</p>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="text-sm font-black text-slate-800">{{ $item->stok }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold ml-1">Buku</span>
                                </td>
                                <td class="px-6 py-6">
                                    @if($item->stok > 0)
                                        <span
                                            class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full uppercase tracking-widest">Tersedia</span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-black rounded-full uppercase tracking-widest">Habis</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-book-open text-5xl text-slate-100 mb-4"></i>
                                        <p class="text-slate-400 font-bold">Belum ada data koleksi buku.</p>
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