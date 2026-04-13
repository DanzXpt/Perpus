@extends('layouts.app')

@section('title','Manajemen Denda')

@section('content')

<div class="max-w-6xl mx-auto space-y-4 animate-fade-in" x-data="{ tab: 'nunggak' }">

    {{-- Header Section --}}
    <div class="flex items-center justify-between px-2">
        <div>
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">
                Manajemen Denda
            </h2>
            <p class="text-xs text-slate-500">Kelola pelunasan denda anggota.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Piutang:</p>
            <p class="text-sm font-black text-red-600">Rp{{ number_format($denda->where('status_denda', 'nunggak')->sum('sisa_denda'), 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex gap-2 px-2">
        <button 
            @click="tab = 'nunggak'" 
            :class="tab === 'nunggak' ? 'bg-indigo-600 text-white shadow-indigo-200' : 'bg-white text-slate-500 hover:bg-slate-50'"
            class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm border border-transparent">
            NUNGGAK ({{ $denda->where('status_denda', 'nunggak')->count() }})
        </button>
        <button 
            @click="tab = 'lunas'" 
            :class="tab === 'lunas' ? 'bg-emerald-600 text-white shadow-emerald-200' : 'bg-white text-slate-500 hover:bg-slate-50'"
            class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm border border-transparent">
            LUNAS ({{ $denda->where('status_denda', 'lunas')->count() }})
        </button>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase">Anggota</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase">Buku</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase text-right">Total</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase text-right">Dibayar</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase text-right text-red-600">Sisa</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase text-center">Status</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-slate-500 uppercase text-center">Aksi Bayar</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($denda as $data)
                    {{-- Row Filter Logic --}}
                    <tr x-show="tab === '{{ $data->status_denda }}'" class="hover:bg-slate-50/50 transition-all">
                        {{-- User --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($data->user->name, 0, 2)) }}
                                </div>
                                <div class="truncate max-w-[100px]">
                                    <p class="text-sm font-bold text-slate-700 truncate leading-none">{{ $data->user->name }}</p>
                                    <span class="text-[9px] text-slate-400 italic">#USR-{{ $data->user->id }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Buku --}}
                        <td class="px-4 py-3">
                            <p class="text-sm text-slate-600 truncate max-w-[130px] font-medium">{{ $data->buku->judul }}</p>
                            <span class="text-[9px] text-slate-400 uppercase tracking-tighter">{{ $data->status }}</span>
                        </td>

                        {{-- Total Denda --}}
                        <td class="px-4 py-3 text-right text-sm text-slate-600 font-medium">
                            {{ number_format($data->denda,0,',','.') }}
                        </td>

                        {{-- Kolom Dibayar --}}
                        <td class="px-4 py-3 text-right text-sm text-emerald-600 font-bold">
                            {{ number_format($data->dibayar,0,',','.') }}
                        </td>

                        {{-- Sisa --}}
                        <td class="px-4 py-3 text-right text-sm font-black text-red-600">
                            {{ number_format($data->sisa_denda,0,',','.') }}
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase {{ $data->status_denda == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $data->status_denda }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3">
                            <div class="flex justify-center">
                                @if($data->status_denda == 'nunggak')
                                <form action="{{ route('petugas.denda.bayar', $data->id) }}" method="POST" class="flex items-center bg-slate-100 rounded-lg p-0.5 border border-slate-200 focus-within:border-indigo-400 transition-colors">
                                    @csrf
                                    <input 
                                        type="number"
                                        name="bayar"
                                        max="{{ $data->sisa_denda }}"
                                        required
                                        class="bg-transparent px-2 py-1 text-xs font-bold text-slate-700 w-16 outline-none"
                                        placeholder="0">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-2.5 py-1 rounded-md text-[9px] font-bold transition-all">
                                        BAYAR
                                    </button>
                                </form>
                                @else
                                <div class="flex items-center gap-1 text-emerald-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-[10px] font-bold uppercase">Lunas</span>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-slate-400 text-xs italic">Data denda tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script for Tab (Jika belum ada Alpine.js di layout utama) --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Chrome, Safari, Edge, Opera - Remove arrows from number input */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
</style>

@endsection