@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4 text-slate-800">Daftar Pengajuan Saya</h1>

    <div class="grid gap-4">
        {{-- PASTIKAN NAMA VARIABELNYA $pengajuan --}}
        @forelse($pengajuan as $p)
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-book"></i>
                </div>
                <div>
                    {{-- PASTIKAN RELASI BUKU SUDAH BENAR --}}
                    <h3 class="font-bold text-slate-800">{{ $p->buku->judul ?? 'Judul Tidak Ada' }}</h3>
                    <p class="text-xs text-slate-500 uppercase font-black">Status: {{ $p->status }}</p>
                </div>
            </div>
            
            <div>
                {{-- WARNA BERDASARKAN STATUS --}}
                @if($p->status == 'pending' || $p->status == 'diajukan')
                    <span class="px-4 py-1.5 bg-amber-100 text-amber-600 rounded-full text-[10px] font-black uppercase">Menunggu</span>
                @elseif($p->status == 'ditolak')
                    <span class="px-4 py-1.5 bg-rose-100 text-rose-600 rounded-full text-[10px] font-black uppercase">Ditolak</span>
                @else
                    <span class="px-4 py-1.5 bg-slate-100 text-slate-400 rounded-full text-[10px] font-black uppercase">{{ $p->status }}</span>
                @endif
            </div>
        </div>
        @empty
            <div class="text-center p-10 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                <p class="text-slate-500">Data terdeteksi di database, tapi gagal tampil di sini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection