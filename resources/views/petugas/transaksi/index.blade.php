@extends('layouts.app')

@section('title', 'Manajemen Transaksi')

@section('content')
    <div class="space-y-6">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm flex justify-between items-center">
            <div>
                <h3 class="font-black text-slate-800 text-xl uppercase italic">Daftar Transaksi</h3>
                <p class="text-slate-400 text-sm font-medium">Kelola pengajuan dan peminjaman buku.</p>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-slate-400 text-[10px] uppercase font-black tracking-widest px-4">
                            <th class="p-4">No</th>
                            <th class="p-4">Anggota / Buku</th>
                            <th class="p-4 text-center">Tgl Pinjam</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Aksi</th>
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
                                        <div class="text-[10px] text-slate-400 uppercase font-bold tracking-tight">
                                            {{ $item->buku->judul }}</div>
                                    </td>
                                    <td class="p-4 text-center text-xs font-bold text-slate-500">
                                        {{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') : '---' }}
                                    </td>
                                    <td class="p-4 text-center">
                                        {{-- Badge Status --}}
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase 
                            @if(in_array(strtolower($item->status), ['pending', 'menunggu'])) bg-amber-100 text-amber-600 
                            @elseif(strtolower($item->status) == 'dipinjam') bg-blue-100 text-blue-600
                            @elseif(strtolower($item->status) == 'terlambat') bg-rose-100 text-rose-600
                            @else bg-emerald-100 text-emerald-600 @endif">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center rounded-r-2xl">
                                        <div class="flex justify-center gap-2">

                                            {{-- LOGIKA AKSI: Jika Status PENDING atau MENUNGGU --}}
                                            @if(in_array(strtolower($item->status), ['pending', 'menunggu']))
                                                <form action="{{ route('petugas.transaksi.setuju', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all">
                                                        Setuju
                                                    </button>
                                                </form>

                                                <form action="{{ route('petugas.transaksi.tolak', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-white border border-rose-200 text-rose-500 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase hover:bg-rose-50 transition-all">
                                                        Tolak
                                                    </button>
                                                </form>

                                                {{-- Jika Status DIPINJAM atau TERLAMBAT --}}
                                            @elseif(in_array(strtolower($item->status), ['dipinjam', 'terlambat']))
                                                <form action="{{ route('petugas.konfirmasi', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-emerald-500 text-white px-4 py-1.5 rounded-lg text-[10px] font-black uppercase hover:bg-emerald-600 shadow-md shadow-emerald-200 transition-all">
                                                        Kembalikan
                                                    </button>
                                                </form>

                                                {{-- Jika Status lainnya (Kembali/Ditolak) --}}
                                            @else
                                                <span
                                                    class="text-slate-300 text-[10px] font-black uppercase italic italic tracking-widest">
                                                    {{ $item->status == 'ditolak' ? 'Ditolak' : 'Selesai' }}
                                                </span>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-10 opacity-50">Data Kosong</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection