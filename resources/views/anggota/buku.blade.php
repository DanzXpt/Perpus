@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
    <div class="container mx-auto px-4">
        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-4xl font-black text-slate-800 tracking-tighter">Katalog Buku</h1>
            <p class="text-slate-400 text-sm font-medium">Temukan inspirasi bacaanmu di sini</p>
        </div>

        {{-- Form Pencarian Tetap Sama --}}
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
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-8 py-4 rounded-2xl transition-all shadow-lg shadow-indigo-200 active:scale-95 text-xs uppercase tracking-widest">
                Cari
            </button>
        </form>

        {{-- Grid Katalog --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse($buku as $b)
                <div
                    class="group bg-white rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl border border-slate-100 transition-all duration-500 flex flex-col">

                    {{-- Bagian Cover: Sekarang BERSIH, tidak ada tombol yang ngalangin --}}
                    <div class="aspect-[3/4] bg-slate-100 relative overflow-hidden">
                        @if($b->cover)
                            <img src="{{ asset('storage/' . $b->cover) }}" alt="{{ $b->judul }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                <i class="fa-solid fa-book text-4xl mb-2"></i>
                                <span class="text-[10px] font-bold uppercase">No Cover</span>
                            </div>
                        @endif

                        {{-- Label Kategori Kecil di Pojok Atas --}}
                        <div class="absolute top-3 left-3">
                            <span
                                class="bg-white/90 backdrop-blur-md text-slate-700 text-[9px] font-black px-3 py-1 rounded-full shadow-sm uppercase border border-white/50">
                                {{ $b->kategori->nama_kategori }}
                            </span>
                        </div>
                    </div>

                    {{-- Konten di Bawah Gambar --}}
                    <div class="p-5 flex-1 flex flex-col">
                        <h5
                            class="font-black text-slate-800 text-sm line-clamp-1 mb-1 group-hover:text-indigo-600 transition-colors uppercase">
                            {{ $b->judul }}
                        </h5>
                        <p class="text-[10px] text-slate-400 font-bold mb-4">By {{ $b->penulis }}</p>

                        {{-- <div class="flex-1"></div> --}}
                        <div class="mt-auto space-y-4">
                            {{-- Info Stok --}}
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase px-2.5 py-1 rounded-lg border border-emerald-100">
                                    <span
                                        class="w-1 h-1 bg-emerald-500 rounded-full {{ $b->stok > 0 ? 'animate-ping' : '' }}"></span>
                                    Stok: {{ $b->stok }}
                                </div>
                            </div>

                            {{-- {Tombol Pinjam: Sekarang posisinya fix di bawah --}}
                            <form action="{{ route('anggota.pinjam.store', $b->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-slate-900 hover:bg-indigo-600 text-white text-[9px] font-black py-4 rounded-2xl transition-all flex items-center justify-center gap-2 uppercase tracking-widest active:scale-95 shadow-lg shadow-slate-100 hover:shadow-indigo-100">
                                    <i class="fa-solid fa-plus-square text-[10px]"></i>
                                    Pinjam Buku
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Buku tidak ditemukan</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection