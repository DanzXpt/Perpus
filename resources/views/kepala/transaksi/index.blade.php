@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Monitoring Transaksi</h1>
            <p class="text-slate-500">Daftar seluruh aktivitas peminjaman dan pengembalian buku.</p>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="p-4 font-bold text-slate-700 border-b border-slate-100">No</th>
                            <th class="p-4 font-bold text-slate-700 border-b border-slate-100">Peminjam</th>
                            <th class="p-4 font-bold text-slate-700 border-b border-slate-100">Buku</th>
                            <th class="p-4 font-bold text-slate-700 border-b border-slate-100">Tgl Pinjam</th>
                            <th class="p-4 font-bold text-slate-700 border-b border-slate-100">Tgl Kembali</th>
                            <th class="p-4 font-bold text-slate-700 border-b border-slate-100">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($transaksi as $t)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 text-slate-600 text-sm">
                                    {{ ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->iteration }}
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-800 text-sm">{{ $t->user->name ?? 'User Terhapus' }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $t->kode_transaksi }}</div>
                                </td>
                                <td class="p-4 text-slate-600 text-sm font-medium">{{ $t->buku->judul ?? 'Buku Terhapus' }}</td>
                                <td class="p-4 text-slate-500 text-sm font-bold">{{ date('d M Y', strtotime($t->tanggal_pinjam)) }}</td>
                                <td class="p-4 text-slate-500 text-sm font-bold">
                                    {{ $t->tanggal_kembali ? date('d M Y', strtotime($t->tanggal_kembali)) : '-' }}
                                </td>
                                <td class="p-4 text-center">
                                    @if($t->status == 'dipinjam')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black uppercase tracking-tighter">Dipinjam</span>
                                    @elseif($t->status == 'terlambat')
                                        <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-[10px] font-black uppercase tracking-tighter">Terlambat</span>
                                    @elseif($t->status == 'pending')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-black uppercase tracking-tighter">Pending</span>
                                    @else
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-tighter">Kembali</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-500 font-bold">
                                    <i class="fas fa-folder-open mb-2 block text-2xl opacity-20"></i>
                                    Belum ada data transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                Menampilkan {{ $transaksi->firstItem() }} - {{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} data
            </p>
            <div>
                {{ $transaksi->links() }}
            </div>
        </div>
    </div>
@endsection