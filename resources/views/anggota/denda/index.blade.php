@extends('layouts.app')

@section('title', 'Riwayat Denda Saya')

@section('content')
    <div class="space-y-6">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
            <h3 class="font-black text-slate-800 text-xl">Tagihan Denda</h3>
            <p class="text-slate-400 text-sm font-medium">Informasi denda keterlambatan pengembalian buku.</p>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase font-black tracking-widest px-4">
                            <th class="p-4">Buku</th>
                            <th class="p-4 text-center">Total Denda</th>
                            <th class="p-4 text-center">Sudah Dibayar</th>
                            <th class="p-4 text-center text-orange-500">Sisa Tagihan</th>
                            <th class="p-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($riwayatDenda as $item)
                            <tr class="bg-white hover:bg-slate-50 transition-all">
                                <td class="p-4">
                                    <div class="font-bold text-slate-800">{{ $item->buku->judul }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">Batas:
                                        {{ \Carbon\Carbon::parse($item->jatuh_tempo)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="p-4 text-center font-black text-red-500">
                                    Rp {{ number_format($item->total_denda, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-center font-black text-emerald-500">
                                    Rp {{ number_format($item->sudah_dibayar, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-center font-black text-orange-600">
                                    Rp {{ number_format($item->sisa_denda_final, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($item->sisa_denda_final > 0)
                                        <span
                                            class="bg-rose-100 text-rose-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                            <i class="fa-solid fa-clock mr-1"></i> Nunggak
                                        </span>
                                    @else
                                        <span
                                            class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                            <i class="fa-solid fa-check-double mr-1"></i> Lunas
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="p-10 text-center text-slate-400 italic font-bold uppercase tracking-widest">
                                    Mantap! Kamu tidak punya denda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection