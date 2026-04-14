@extends('layouts.app')

@section('content')
<div class="space-y-10 animate-fade-in pb-10">

    {{-- ================= PEMINJAMAN AKTIF ================= --}}
    <div>
        <div class="px-2">
            <h1 class="text-2xl font-bold text-slate-800">Peminjaman Aktif</h1>
            <p class="text-sm text-slate-500">Daftar buku yang sedang Anda pinjam atau dalam pengajuan.</p>
        </div>

        <div class="mt-4 bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Buku</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Tgl Pinjam</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Jatuh Tempo</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Denda</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        @forelse($riwayat->whereIn('status', ['dipinjam', 'terlambat', 'pending', 'PENDING']) as $data)
                            @php
                                $statusDb = strtolower($data->status);
                                // Set ke akhir hari agar denda terhitung tepat setelah hari berganti
                                $deadline = $data->jatuh_tempo ? \Carbon\Carbon::parse($data->jatuh_tempo)->endOfDay() : null;
                                $hariIni = now();

                                // Logika Terlambat
                                $isTerlambat = ($deadline && $hariIni->greaterThan($deadline));
                                
                                // Hitung denda real-time (Rp 1.000 / hari)
                                $dendaRealtime = 0;
                                if ($isTerlambat) {
                                    $selisihHari = $hariIni->diffInDays($deadline);
                                    $dendaRealtime = ($selisihHari + 1) * 1000; 
                                }

                                // Gabungkan denda dari DB (jika ada) dengan denda kalkulasi
                                $totalDenda = max($data->denda, $dendaRealtime);
                                $tampilkanTerlambat = ($statusDb === 'terlambat' || $isTerlambat);
                            @endphp

                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                {{-- Kolom Buku --}}
                                <td class="px-6 py-4 flex items-center gap-4">
                                    <div class="w-10 h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0 shadow-sm group-hover:shadow-md transition-shadow">
                                        @if($data->buku->cover)
                                            <img src="{{ asset('storage/' . $data->buku->cover) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[8px] text-slate-400 text-center uppercase p-1">No Cover</div>
                                        @endif
                                    </div>
                                    <span class="font-bold text-slate-700 text-sm line-clamp-2 uppercase italic tracking-tighter">
                                        {{ $data->buku->judul }}
                                    </span>
                                </td>

                                {{-- Tanggal Pinjam --}}
                                <td class="px-6 py-4 text-sm text-center font-medium text-slate-500">
                                    {{ $data->tanggal_pinjam ? \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d M Y') : '-' }}
                                </td>

                                {{-- Jatuh Tempo --}}
                                <td class="px-6 py-4 text-sm text-center {{ $isTerlambat ? 'text-red-500 font-bold' : 'text-slate-500 font-medium' }}">
                                    {{ $data->jatuh_tempo ? \Carbon\Carbon::parse($data->jatuh_tempo)->format('d M Y') : '-' }}
                                    @if($isTerlambat)
                                        <span class="block text-[8px] uppercase tracking-tighter animate-pulse text-red-400">Overdue</span>
                                    @endif
                                </td>

                                {{-- Denda --}}
                                <td class="px-6 py-4 text-sm text-center font-bold {{ $totalDenda > 0 ? 'text-red-500' : 'text-slate-400' }}">
                                    Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest 
                                    {{ $statusDb === 'pending' ? 'bg-amber-100 text-amber-600' : 
                                        ($tampilkanTerlambat ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600') }}">
                                        
                                        @if($statusDb === 'pending')
                                            PENDING
                                        @elseif($tampilkanTerlambat)
                                            TERLAMBAT
                                        @else
                                            DIPINJAM
                                        @endif
                                    </span>
                                </td>

                                {{-- Tombol Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    @if(in_array($statusDb, ['dipinjam', 'terlambat']))
                                        <form action="{{ route('anggota.kembalikan', $data->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')">
                                            @csrf
                                            <button type="submit" class="bg-indigo-50 text-indigo-600 px-4 py-1.5 rounded-xl text-[10px] font-black hover:bg-indigo-600 hover:text-white transition-all transform hover:scale-105 uppercase italic shadow-sm">
                                                Kembalikan
                                            </button>
                                        </form>
                                    @elseif($statusDb === 'pending')
                                        <span class="text-[10px] font-bold text-slate-400 uppercase italic">Menunggu ACC</span>
                                    @else
                                        <span class="text-[10px] font-bold text-emerald-500 uppercase italic">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-slate-400 italic font-medium">
                                    Belum ada buku yang sedang dipinjam atau dalam pengajuan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ================= RIWAYAT PENGEMBALIAN ================= --}}
    <div>
        <div class="px-2">
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Pengembalian</h1>
            <p class="text-sm text-slate-500">Daftar buku yang sudah selesai dikembalikan.</p>
        </div>

        <div class="mt-4 bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Buku</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Tgl Kembali</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Denda Akhir</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        @forelse($riwayat->where('status', 'kembali') as $data)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 flex items-center gap-4">
                                    <div class="w-10 h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0 shadow-sm">
                                        @if($data->buku->cover)
                                            <img src="{{ asset('storage/' . $data->buku->cover) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-[8px] text-slate-400">NO IMG</div>
                                        @endif
                                    </div>
                                    <span class="font-bold text-slate-700 text-sm uppercase italic tracking-tighter">
                                        {{ $data->buku->judul }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center text-sm font-medium text-slate-500">
                                    {{ $data->tanggal_kembali ? \Carbon\Carbon::parse($data->tanggal_kembali)->format('d M Y') : '-' }}
                                </td>

                                <td class="px-6 py-4 text-center font-bold {{ $data->denda > 0 ? 'text-red-500' : 'text-emerald-600' }}">
                                    Rp {{ number_format($data->denda, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($data->denda > 0)
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black bg-red-100 text-red-600 uppercase tracking-widest">
                                            TERLAMBAT
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-600 uppercase tracking-widest">
                                            TEPAT WAKTU
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-slate-400 italic font-medium">
                                    Belum ada riwayat pengembalian buku.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection