@extends('layouts.app')

@section('title', 'Manajemen Transaksi')

@section('content')
    <div class="space-y-6">
        {{-- Header Card --}}
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <h3 class="font-black text-slate-800 text-xl">Daftar Peminjaman</h3>
                <p class="text-slate-400 text-sm font-medium">Kelola semua aktivitas pinjam-meminjam buku.</p>
            </div>
            <div class="bg-indigo-50 text-indigo-600 px-6 py-2 rounded-2xl font-bold text-sm">
                Total: {{ $transaksi->total() }} Data
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase font-black tracking-widest px-4">
                            <th class="p-4">No</th>
                            <th class="p-4">Anggota</th>
                            <th class="p-4">Judul Buku</th>
                            <th class="p-4 text-center">Tgl Pinjam</th>
                            <th class="p-4 text-center">Tgl Kembali</th>
                            <th class="p-4 text-center">Status / Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $item)
                            <tr class="bg-slate-50/50 hover:bg-indigo-50/50 transition-all group">
                                <td class="p-4 rounded-l-2xl font-bold text-slate-400 text-sm">
                                    {{ ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->iteration }}
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-800 text-sm">{{ $item->user->name }}</div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold tracking-tight">ID User:
                                        #{{ $item->user->id }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="font-medium text-slate-600 text-sm line-clamp-1">{{ $item->buku->judul }}</div>
                                </td>
                                <td class="p-4 text-center text-xs font-bold text-slate-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}
                                </td>
                                <td class="p-4 text-center text-xs font-bold text-slate-500">
                                    {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d M Y') }}
                                </td>
                                <td class="p-4 text-center rounded-r-2xl">
                                    @if($item->status == 'dipinjam')
                                        <form action="{{ route('petugas.kembali', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT') <!-- wajib biar Laravel ngerti ini PUT -->
                                            <button type="submit"
                                                class="bg-emerald-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-emerald-600 transition-all">
                                                <i class="fa-solid fa-rotate-left mr-1"></i> konfirmasi
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="bg-emerald-100 text-emerald-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase">
                                            <i class="fa-solid fa-check mr-1"></i> Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-20">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fa-solid fa-folder-open text-5xl mb-4 text-indigo-200"></i>
                                        <p class="font-bold text-slate-500 italic">Belum ada transaksi peminjaman.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-8 bg-slate-50/50 border-t border-slate-100">
                {{ $transaksi->links() }}
            </div>
        </div>
    </div>
@endsection