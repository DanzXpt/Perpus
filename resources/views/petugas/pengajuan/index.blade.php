@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-10">
        {{-- 1. BAGIAN PENDING (KARTU) --}}
        <div>
            <h1 class="text-2xl font-bold mb-6 text-slate-800 flex items-center gap-2">
                <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                Persetujuan Pinjam (Pending)
            </h1>

            <div class="grid gap-4">
                @forelse($pengajuan->where('status', 'pending') as $p)
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-800">{{ $p->user->name }}</h3>
                            <p class="text-sm text-slate-500">Ingin meminjam: <span class="font-semibold text-indigo-600">{{ $p->buku->judul }}</span></p>
                            <p class="text-xs text-slate-400 mt-1 italic">Diajukan pada: {{ $p->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <div class="flex gap-2">
                            <form action="{{ url('/petugas/pengajuan/' . $p->id . '/setujui') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 px-4 py-2 text-white text-xs font-bold rounded-xl transition">
                                    Setujui
                                </button>
                            </form>

                            <form action="{{ url('/petugas/pengajuan/' . $p->id . '/tolak') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-rose-500 hover:bg-rose-600 px-4 py-2 text-white text-xs font-bold rounded-xl transition">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-12 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                        <p class="text-slate-500 font-medium">Tidak ada pengajuan pinjaman baru.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <hr class="border-slate-100">

        {{-- 2. BAGIAN RIWAYAT (TABEL) --}}
        <div>
            <h2 class="text-xl font-bold mb-6 text-slate-800 flex items-center gap-2">
                <span class="w-2 h-8 bg-slate-800 rounded-full"></span>
                Riwayat Keputusan
            </h2>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Peminjam</th>
                            <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Buku</th>
                            <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Proses</th>
                            <th class="p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($pengajuan->whereIn('status', ['dipinjam', 'ditolak', 'kembali']) as $r)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4">
                                    <div class="font-bold text-slate-700 text-sm">{{ $r->user->name }}</div>
                                </td>
                                <td class="p-4 text-sm text-slate-600">{{ $r->buku->judul }}</td>
                                <td class="p-4 text-xs text-slate-400">{{ $r->updated_at->format('d M Y H:i') }}</td>
                                <td class="p-4 text-center">
                                    @if($r->status == 'ditolak')
                                        <span class="px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-full text-[10px] font-black uppercase">Ditolak</span>
                                    @else
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-black uppercase">Disetujui</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-400 text-sm italic">Belum ada riwayat keputusan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection