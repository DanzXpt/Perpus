@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<div class="container mx-auto px-4 pb-20">

    {{-- Header --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-800 tracking-tighter">Katalog Buku</h1>
            <p class="text-slate-400 text-sm font-medium">Temukan inspirasi bacaanmu di sini</p>
        </div>

        <div class="hidden md:flex gap-4">
            <div class="bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm">
                <span class="block text-[10px] text-slate-400 font-black uppercase">Total Buku</span>
                <span class="text-xl font-black text-indigo-600">{{ $buku->total() }}</span>
            </div>
        </div>
    </div>

    {{-- Search --}}
    <form action="{{ route('anggota.buku') }}" method="GET"
        class="bg-white p-3 rounded-3xl shadow-sm border border-slate-100 mb-12 flex flex-wrap gap-3 items-center">

        <div class="relative flex-1 min-w-[250px]">
            <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari judul atau penulis..."
                class="w-full pl-12 pr-6 py-4 bg-slate-50 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none font-semibold">
        </div>

        <select name="kategori"
            class="bg-slate-50 text-slate-600 text-sm py-4 px-6 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>

        <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-4 rounded-2xl text-xs uppercase">
            Cari
        </button>
    </form>

    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        @forelse($buku as $b)
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-sm border flex flex-col">

            {{-- Cover --}}
            <div class="aspect-[3/4] bg-slate-100">
                @if($b->cover)
                    <img src="{{ asset('storage/' . $b->cover) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                        No Image
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div class="p-5 flex flex-col flex-1">

                <h5 class="font-bold text-sm mb-1 line-clamp-2">
                    {{ $b->judul }}
                </h5>

                <p class="text-xs text-slate-500 mb-2">
                    {{ $b->penulis }}
                </p>

                {{-- Stok --}}
                <p class="text-xs font-bold mb-3">
                    {{ $b->stok > 0 ? 'Stok: '.$b->stok : 'Stok Habis' }}
                </p>

                {{-- Tombol --}}
                <div class="mt-auto">

                    @if($b->stok > 0 && $bolehPinjam)
                        <form action="{{ route('anggota.pinjam.store', $b->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-black text-white text-xs py-3 rounded-xl">
                                Pinjam Buku
                            </button>
                        </form>

                    @elseif($b->stok > 0 && !$bolehPinjam)
                        <button disabled
                            class="w-full bg-red-100 text-red-400 text-xs py-3 rounded-xl">
                            Limit Tercapai
                        </button>

                    @else
                        <button disabled
                            class="w-full bg-gray-200 text-gray-400 text-xs py-3 rounded-xl">
                            Tidak Tersedia
                        </button>
                    @endif

                </div>
            </div>
        </div>

        @empty
            <p class="col-span-full text-center text-slate-400">
                Buku tidak ditemukan
            </p>
        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $buku->links() }}
    </div>

</div>
@endsection