@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <div class="container mx-auto px-4 pb-20">
        <div class="flex flex-col md:flex-row gap-10">
            {{-- Cover --}}
            <div class="w-full md:w-1/3">
                <div class="bg-slate-100 rounded-2xl overflow-hidden aspect-[3/4]">
                    @if($buku->cover)
                        <img src="{{ asset('storage/' . $buku->cover) }}" alt="{{ $buku->judul }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <i class="fa-solid fa-book-bookmark text-5xl"></i>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="flex-1 flex flex-col gap-6">
                <h1 class="text-3xl font-black text-slate-800 uppercase">{{ $buku->judul }}</h1>
                <p class="text-sm text-slate-500">OLEH <span class="font-bold text-slate-700">{{ $buku->penulis }}</span>
                </p>
                <p class="text-sm text-slate-500">KATEGORI: <span
                        class="font-bold text-slate-700">{{ $buku->kategori?->nama_kategori ?? 'Tanpa Kategori' }}</span>
                </p>
                <p class="text-sm text-slate-500">TAHUN TERBIT: <span
                        class="font-bold text-slate-700">{{ $buku->tahun_terbit }}</span></p>
                <p class="text-sm text-slate-500">STOK:
                    <span class="font-bold {{ $buku->stok > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $buku->stok > 0 ? $buku->stok : 'Habis' }}
                    </span>
                </p>

                <p class="text-sm text-slate-500">DESKRIPSI:</p>
                <p class="text-[10px] text-slate-500 mt-2 line-clamp-3">
                    {{ $buku->deskripsi ?? 'Deskripsi belum tersedia' }}
                </p>

                {{-- Tombol Pinjam --}}
                @if($buku->stok > 0)
                    <form action="{{ route('anggota.pinjam.store', $buku->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl font-black uppercase text-xs transition-all shadow-md active:scale-95">
                            Pinjam Buku
                        </button>
                    </form> 
                @else
                    <button disabled
                        class="w-full bg-slate-200 text-slate-400 py-4 rounded-2xl font-black uppercase text-xs cursor-not-allowed">
                        Stok Habis
                    </button>
                @endif

                <a href="{{ route('anggota.buku') }}"
                    class="text-indigo-600 text-xs font-black mt-4 hover:underline">Kembali ke Katalog</a>
            </div>
        </div>
    </div>
@endsection