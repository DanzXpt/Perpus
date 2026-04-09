@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Monitoring Transaksi</h1>
            <p class="text-slate-500">Daftar seluruh aktivitas peminjaman dan pengembalian buku.</p>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
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
                <tbody>
                    @forelse($transaksi as $t)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 text-slate-600">
                                {{ ($transaksi->currentPage() - 1) * $transaksi->perPage() + $loop->iteration }}</td>
                            <td class="p-4 font-medium text-slate-800">{{ $t->user->name ?? 'User Terhapus' }}</td>
                            <td class="p-4 text-slate-600">{{ $t->buku->judul ?? 'Buku Terhapus' }}</td>
                            <td class="p-4 text-slate-600">{{ date('d M Y', strtotime($t->tanggal_pinjam)) }}</td>
                            <td class="p-4 text-slate-600">{{ date('d M Y', strtotime($t->tanggal_kembali)) }}</td>
                            <td class="p-4">
                                @if($t->status == 'dipinjam')
                                    <span
                                        class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase">Dipinjam</span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold uppercase">Kembali</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-500">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
            <div class="mt-6 flex justify-between items-center">
                <p class="text-sm text-slate-500">
                    Menampilkan {{ $transaksi->firstItem() }} - {{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} transaksi
                </p>

                {{ $transaksi->links() }}
            </div>
    </div>
@endsection