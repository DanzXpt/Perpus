@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
    <div class="container mx-auto px-4 pb-20">
        {{-- Header Section --}}
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black text-slate-800 tracking-tighter">Katalog Buku</h1>
                <p class="text-slate-400 text-sm font-medium">Temukan inspirasi bacaanmu di sini</p>
            </div>
            {{-- Badge Info Cepat --}}
            <div class="hidden md:flex gap-4">
                <div class="bg-white px-6 py-3 rounded-2xl border border-slate-100 shadow-sm">
                    <span class="block text-[10px] text-slate-400 font-black uppercase tracking-widest">Total Buku</span>
                    <span class="text-xl font-black text-indigo-600">{{ $buku->total() }}</span>
                </div>
            </div>
        </div>

        {{-- Form Pencarian --}}
        <form action="{{ route('anggota.buku') }}" method="GET"
            class="bg-white p-3 rounded-3xl shadow-sm border border-slate-100 mb-12 flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-[250px]">
                <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau penulis..."
                    class="w-full pl-12 pr-6 py-4 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all outline-none font-semibold">
            </div>

            <select name="kategori"
                class="bg-slate-50 border-none text-slate-600 text-sm py-4 px-6 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer font-bold">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-4 rounded-2xl transition-all shadow-lg shadow-indigo-200 active:scale-95 text-xs uppercase tracking-widest">
                Cari
            </button>
        </form>

        {{-- Grid Katalog --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-8">
            @forelse($buku as $b)
                <div class="group bg-white rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl border border-slate-100 transition-all duration-500 flex flex-col h-full">

                    {{-- Bagian Cover --}}
                    <div class="aspect-[3/4] bg-slate-100 relative overflow-hidden">
                        @if($b->cover)
                            <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->judul }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-gradient-to-br from-slate-50 to-slate-100">
                                <i class="fa-solid fa-book-bookmark text-5xl mb-2 opacity-20"></i>
                                <span class="text-[10px] font-black uppercase tracking-tighter">No Preview</span>
                            </div>
                        @endif

                        {{-- Floating Kategori --}}
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/80 backdrop-blur-md text-slate-700 text-[9px] font-black px-3 py-1.5 rounded-xl shadow-sm uppercase border border-white/50">
                                {{ $b->kategori->nama_kategori }}
                            </span>
                        </div>
                    </div>

                    {{-- Konten --}}
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-4">
                            <h5 class="font-black text-slate-800 text-sm line-clamp-2 mb-1 group-hover:text-indigo-600 transition-colors uppercase leading-tight">
                                {{ $b->judul }}
                            </h5>
                            <p class="text-[10px] text-slate-400 font-bold tracking-wide">OLEH <span class="text-slate-600">{{ $b->penulis }}</span></p>
                        </div>

                        <div class="mt-auto space-y-4">
                            {{-- Status & Stok --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 {{ $b->stok > 0 ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }} text-[9px] font-black uppercase px-3 py-1.5 rounded-xl border">
                                    <span class="w-1.5 h-1.5 {{ $b->stok > 0 ? 'bg-emerald-500 animate-ping' : 'bg-rose-500' }} rounded-full"></span>
                                    {{ $b->stok > 0 ? 'Tersedia: ' . $b->stok : 'Stok Habis' }}
                                </div>
                                <span class="text-[10px] font-black text-slate-300 uppercase">{{ $b->tahun_terbit }}</span>
                            </div>

                            {{-- Tombol Pinjam --}}
                            @if($b->stok > 0)
                                <form action="{{ route('anggota.pinjam.store', $b->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-slate-900 hover:bg-indigo-600 text-white text-[10px] font-black py-4 rounded-2xl transition-all flex items-center justify-center gap-2 uppercase tracking-widest active:scale-95 shadow-xl shadow-slate-100 hover:shadow-indigo-200">
                                        <i class="fa-solid fa-plus-square"></i>
                                        Pinjam Buku
                                    </button>
                                </form>
                            @else
                                <button disabled
                                    class="w-full bg-slate-100 text-slate-400 text-[10px] font-black py-4 rounded-2xl flex items-center justify-center gap-2 uppercase tracking-widest cursor-not-allowed">
                                    <i class="fa-solid fa-ban"></i>
                                    Tidak Tersedia
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-100">
                    <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-search text-slate-200 text-2xl"></i>
                    </div>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Buku tidak ditemukan</p>
                    <a href="{{ route('anggota.buku') }}" class="text-indigo-600 text-[10px] font-black uppercase mt-4 inline-block hover:underline">Reset Pencarian</a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-16">
            {{ $buku->appends(request()->query())->links() }}
        </div>
    </div>
@endsection