@extends('layouts.app')

@section('title','Manajemen Denda')

@section('content')

{{-- Tambahkan script alpine jika belum ada di layout utama --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="max-w-6xl mx-auto space-y-4 animate-fade-in" x-data="{ tab: 'nunggak' }">

    {{-- Header Section --}}
    <div class="flex items-center justify-between px-2">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Manajemen Denda</h2>
            <p class="text-xs text-slate-500">Konfirmasi pelunasan denda anggota secara langsung.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Piutang:</p>
            <p class="text-sm font-black text-red-600">Rp{{ number_format($denda->where('status_denda', 'nunggak')->sum('denda'), 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex gap-2 px-2">
        <button 
            @click="tab = 'nunggak'" 
            :class="tab === 'nunggak' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-slate-500 hover:bg-slate-50'"
            class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all border border-transparent">
            NUNGGAK ({{ $denda->where('status_denda', 'nunggak')->count() }})
        </button>
        <button 
            @click="tab = 'lunas'" 
            :class="tab === 'lunas' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-slate-500 hover:bg-slate-50'"
            class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all border border-transparent">
            LUNAS ({{ $denda->where('status_denda', 'lunas')->count() }})
        </button>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase">Anggota</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase">Buku</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-right">Nominal</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($denda as $data)
                    {{-- Alpine.js Filter --}}
                    <tr x-show="tab === '{{ $data->status_denda }}'" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        class="hover:bg-slate-50/50 transition-all">
                        
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-[10px]">
                                    {{ strtoupper(substr($data->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700 leading-none">{{ $data->user->name }}</p>
                                    <span class="text-[9px] text-slate-400">ID: #{{ $data->user->id }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $data->buku->judul }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="text-sm font-black text-rose-600">
                                Rp{{ number_format($data->denda, 0, ',', '.') }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $data->status_denda == 'lunas' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $data->status_denda }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center">
                                @if($data->status_denda == 'nunggak')
                                    <form action="{{ route('petugas.bayar-lunas', $data->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Lunasi denda Rp{{ number_format($data->denda) }}?')"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-lg text-[10px] font-bold transition-all uppercase">
                                            Bayar Lunas
                                        </button>
                                    </form>
                                @else
                                    <span class="flex items-center gap-1 text-emerald-600 font-bold text-[10px] uppercase italic">
                                        ✓ Terbayar 
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs italic">
                            Belum ada riwayat denda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

@endsection