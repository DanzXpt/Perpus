@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat & Peminjaman Aktif</h1>
            <p class="text-sm text-slate-500">Daftar buku yang sedang Anda pinjam atau sudah dikembalikan.</p>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Buku</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Tgl
                            Pinjam</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">
                            Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($riwayat as $data)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-10 h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0 shadow-sm">
                                    <img src="{{ asset('storage/' . $data->buku->cover) }}" class="w-full h-full object-cover">
                                </div>
                                <span class="font-bold text-slate-700 text-sm">{{ $data->buku->judul }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 text-center">
                                {{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $color = match ($data->status) {
                                        'dipinjam' => 'bg-blue-100 text-blue-600',
                                        'kembali' => 'bg-green-100 text-green-600',
                                        default => 'bg-gray-100 text-gray-600'
                                    };
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $color }}">
                                    {{ $data->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($data->status == 'dipinjam')
                                    <form action="{{ route('anggota.kembalikan', $data->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')">
                                        @csrf
                                        <button type="submit"
                                            class="text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition">
                                            Kembalikan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-slate-400 font-bold uppercase italic">Selesai</span>
                                @endif
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