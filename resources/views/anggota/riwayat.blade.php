@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Riwayat Peminjaman</h1>
        <p class="text-sm text-slate-500">Pantau status peminjaman dan tanggal pengembalian buku Anda.</p>
    </div>

    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Buku</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tgl Pinjam</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Tgl Kembali</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($riwayat as $data)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="w-10 h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0 shadow-sm">
                             <img src="{{ asset('storage/'.$data->buku->cover) }}" class="w-full h-full object-cover">
                        </div>
                        <span class="font-bold text-slate-700 text-sm">{{ $data->buku->judul }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $data->tanggal_pinjam }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $data->tanggal_kembali ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $color = match($data->status) {
                                'diajukan' => 'bg-amber-100 text-amber-600',
                                'dipinjam' => 'bg-blue-100 text-blue-600',
                                'kembali' => 'bg-green-100 text-green-600',
                                'ditolak' => 'bg-red-100 text-red-600',
                                default => 'bg-gray-100 text-gray-600'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $color }}">
                            {{ $data->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic text-sm">
                        Belum ada riwayat peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection