@extends('layouts.app')

@section('content')

    <div class="p-6">

        ```
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Kelola Koleksi Buku</h2>
                <p class="text-slate-500 text-sm">Tambah, edit, atau hapus data buku perpustakaan.</p>
            </div>

            <a href="{{ route('petugas.buku.create') }}"
                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-all flex items-center shadow-lg shadow-indigo-200">
                <i class="fas fa-plus mr-2 text-xs"></i> Tambah Buku Baru
            </a>
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div
                class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left">

                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Cover</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Judul</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Penulis</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Penerbit</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Tahun</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">Stok</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($buku as $item)
                                <tr class="hover:bg-slate-50 transition">

                                    {{-- ID --}}
                                    <td class="px-6 py-4 font-bold text-slate-700">
                                        {{ $buku->firstItem() + $loop->index }}
                                    </td>

                                    {{-- Cover --}}
                                    <td class="px-6 py-4">
                                        @if($item->cover)
                                            <img src="{{ asset('storage/' . $item->cover) }}"
                                                class="w-14 h-20 object-cover rounded-lg shadow">
                                        @else
                                            <div
                                                class="w-14 h-20 bg-slate-200 rounded-lg flex items-center justify-center text-xs text-slate-500">
                                                No Cover
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Judul --}}
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        {{ $item->judul }}
                                    </td>

                                    {{-- Penulis --}}
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $item->penulis }}
                                    </td>

                                    {{-- Penerbit --}}
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $item->penerbit }}
                                    </td>

                                    {{-- Tahun --}}
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ $item->tahun_terbit }}
                                    </td>

                                    {{-- Stok --}}
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-xl text-xs font-bold
                                        {{ $item->stok > 0
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-rose-100 text-rose-700' }}">
                                            {{ $item->stok }} Buku
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">

                                            <a href="{{ route('petugas.buku.edit', $item->id) }}"
                                                class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('petugas.buku.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus buku ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-slate-400 italic">
                                Belum ada data buku.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-between items-center">
            <p class="text-sm text-slate-500">
                Menampilkan {{ $buku->firstItem() }} - {{ $buku->lastItem() }} dari {{ $buku->total() }} buku
            </p>

            {{ $buku->links() }}
        </div>
    </div>
@endsection