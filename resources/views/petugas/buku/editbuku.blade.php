@extends('layouts.app')

@section('content')

    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-black text-slate-800">Edit Data Buku</h2>
        <a href="{{ route('petugas.buku.index') }}"
            class="text-slate-500 hover:text-indigo-600 font-bold transition-all text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
        {{-- SATU FORM UNTUK SEMUA --}}
        <form action="{{ route('petugas.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Judul
                        Buku</label>
                    <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-0 outline-none font-bold text-slate-700">
                </div>

                {{-- Penulis --}}
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Penulis</label>
                    <input type="text" name="penulis" value="{{ old('penulis', $buku->penulis) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Penerbit --}}
                    <div>
                        <label
                            class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Penerbit</label>
                        <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700">
                    </div>
                    {{-- Tahun --}}
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tahun
                            Terbit</label>
                        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    {{-- Stok --}}
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Jumlah
                            Stok</label>
                        <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700">
                    </div>
                    {{-- Kategori (YANG TADI HILANG) --}}
                    {{-- Ganti bagian select kategori dengan ini --}}
                    <div>
                        <label
                            class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
                        <select name="kategori_id" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700">
                            {{-- Pastikan nama variabelnya sama dengan yang dikirim Controller (biasanya $kategoris)
                            --}}
                            @foreach($kategori as $k)
                                <option value="{{ $k->id }}" {{ $buku->kategori_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Cover Buku --}}
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ganti Cover
                        (Opsional)</label>
                    <div class="flex items-center gap-4">
                        @if($buku->cover)
                            <img src="{{ asset('storage/' . $buku->cover) }}" class="w-20 h-24 object-cover rounded-lg border">
                        @endif
                        <input type="file" name="cover"
                            class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full px-4 py-2 border rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none font-bold">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                </div>




                <button type="submit"
                    class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all mt-4">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    </div>
@endsection