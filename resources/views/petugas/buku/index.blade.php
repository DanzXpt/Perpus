@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Kelola Koleksi Buku</h2>
            <p class="text-slate-500 text-sm">Tambah, edit, atau hapus data buku perpustakaan.</p>
        </div>
        <a href="{{ route('petugas.buku.create') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all flex items-center shadow-lg shadow-indigo-200">
            <i class="fas fa-plus mr-2 text-xs"></i> Tambah Buku Baru
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Judul & Penulis</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Penerbit</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($buku as $item)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800">{{ $item->judul }}</div>
                        <div class="text-xs text-slate-400">{{ $item->penulis }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $item->penerbit }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 {{ $item->stok > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} rounded-full text-xs font-bold">
                            {{ $item->stok }} Buku
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('petugas.buku.edit', $item->id) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('petugas.buku.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">
                        Belum ada data buku. Klik "Tambah Buku Baru" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>  
</div>
@endsection