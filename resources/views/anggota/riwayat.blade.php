@extends('layouts.app')

@section('content')
    <div class="space-y-10">

        {{-- ================= PEMINJAMAN AKTIF ================= --}}
        <div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Peminjaman Aktif</h1>
                <p class="text-sm text-slate-500">Daftar buku yang sedang Anda pinjam saat ini.</p>
            </div>

            <div class="mt-4 bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase">Buku</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-center">Tgl Pinjam</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-center">Jatuh Tempo
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-center">Denda</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        {{-- Menampilkan semua yang belum kembali (dipinjam ATAU terlambat) --}}
                        @forelse($riwayat->whereIn('status', ['dipinjam', 'terlambat', 'pending']) as $data)
                            @php
                                $deadline = \Carbon\Carbon::parse($data->jatuh_tempo)->startOfDay();
                                $hariIni = now()->startOfDay();

                                // Hitung selisih hari jika sudah lewat deadline
                                $selisih = $deadline->diffInDays($hariIni, false);
                                $isTerlambat = $hariIni > $deadline;
                                $dendaRealtime = $isTerlambat ? $selisih * 5000 : 0;
                            @endphp

                            <tr class="hover:bg-slate-50/50 transition">
                                {{-- Buku --}}
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-10 h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0">
                                        <img src="{{ asset('storage/' . $data->buku->cover) }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <span class="font-bold text-slate-700 text-sm line-clamp-2">
                                        {{ $data->buku->judul }}
                                    </span>
                                </td>

                                {{-- Tanggal Pinjam --}}
                                <td class="px-6 py-4 text-sm text-center">
                                    {{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d M Y') }}
                                </td>

                                {{-- Jatuh Tempo --}}
                                <td
                                    class="px-6 py-4 text-sm text-center {{ $isTerlambat ? 'text-red-500 font-bold' : 'text-slate-600' }}">
                                    {{ \Carbon\Carbon::parse($data->jatuh_tempo)->format('d M Y') }}
                                </td>

                                {{-- Denda Real Time --}}
                                <td
                                    class="px-6 py-4 text-sm text-center font-bold {{ $isTerlambat ? 'text-red-500' : 'text-slate-600' }}">
                                    Rp {{ number_format($dendaRealtime, 0, ',', '.') }}
                                </td>

                                {{-- Status --}}
                                {{-- Status --}}
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $deadline = \Carbon\Carbon::parse($data->jatuh_tempo)->startOfDay();
                                        $hariIni = now()->startOfDay();

                                        // Cek apakah status di DB sudah 'terlambat' ATAU secara tanggal memang sudah telat
                                        $statusDb = strtolower($data->status);
                                        $telatSecaraTanggal = $hariIni > $deadline;

                                        $tampilkanTerlambat = ($statusDb === 'terlambat' || $telatSecaraTanggal);
                                    @endphp

                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold 
                                    {{ $tampilkanTerlambat ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">

                                        {{-- Jika status di DB 'pending', tampilkan PENDING,
                                        jika terlambat (di DB/Tanggal), tampilkan TERLAMBAT,
                                        selain itu DIPINJAM --}}
                                        @if($statusDb === 'pending')
                                            PENDING
                                        @elseif($tampilkanTerlambat)
                                            TERLAMBAT
                                        @else
                                            DIPINJAM
                                        @endif
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('anggota.kembalikan', $data->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin mengembalikan buku ini?')">
                                        @csrf
                                        <button type="submit" class="text-xs font-black text-indigo-600 hover:text-indigo-800">
                                            Kembalikan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                    Belum ada buku yang sedang dipinjam.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        {{-- ================= RIWAYAT PENGEMBALIAN ================= --}}
        <div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Riwayat Pengembalian</h1>
                <p class="text-sm text-slate-500">Daftar buku yang sudah selesai dikembalikan.</p>
            </div>

            <div class="mt-4 bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-gray-100">
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase">Buku</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-center">Tgl Kembali
                            </th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-center">Denda</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-slate-500 uppercase text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">
                        {{-- Menampilkan yang statusnya sudah kembali --}}
                        @forelse($riwayat->where('status', 'kembali') as $data)
                            <tr class="hover:bg-slate-50/50">

                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-10 h-14 bg-slate-100 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $data->buku->cover) }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <span class="font-bold text-slate-700 text-sm">
                                        {{ $data->buku->judul }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center text-sm">
                                    {{ \Carbon\Carbon::parse($data->tanggal_kembali)->format('d M Y') }}
                                </td>

                                <td
                                    class="px-6 py-4 text-center font-bold 
                                                                                {{ $data->denda > 0 ? 'text-red-500' : 'text-green-600' }}">
                                    Rp {{ number_format($data->denda, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($data->denda > 0)
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600">
                                            TERLAMBAT
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-600">
                                            SELESAI
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                    Belum ada riwayat pengembalian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection