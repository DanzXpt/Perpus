@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    {{-- Notifikasi Error Global --}}
    @if ($errors->any())
        <div class="bg-rose-100 text-rose-700 p-4 rounded-2xl mb-6 border border-rose-200">
            <ul class="list-disc pl-5 font-bold text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-black text-slate-800">Tambah Buku Baru</h2>
        <a href="{{ route('petugas.buku.index') }}" class="text-slate-500 hover:text-indigo-600 font-bold transition-all text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
        <form action="{{ route('petugas.buku.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 gap-6">
                {{-- Upload Cover --}}
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Cover Buku</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="cover-upload" class="flex flex-col items-center justify-center w-full h-48 border-2 border-slate-200 border-dashed rounded-[2rem] cursor-pointer bg-slate-50 hover:bg-slate-100 transition-all overflow-hidden relative">
                            <div id="preview-container" class="absolute inset-0 hidden">
                                <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-contain p-2">
                            </div>

                            <div id="upload-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-image text-3xl text-slate-300 mb-3"></i>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Klik untuk upload (Max 2MB)</p>
                            </div>
                            <input id="cover-upload" name="cover" type="file" class="hidden" accept="image/*" onchange="previewImage(this)"/>
                        </label>
                    </div>
                </div>

                {{-- Judul & Penulis --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Judul Buku</label>
                        <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Judul..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Penulis</label>
                        <input type="text" name="penulis" value="{{ old('penulis') }}" required placeholder="Penulis..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700">
                    </div>
                </div>

                {{-- Kategori (TAMBAHKAN INI) --}}
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Kategori Buku</label>
                    <select name="kategori_id" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700 appearance-none">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}" {{ old('kategori_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Penerbit</label>
                        <input type="text" name="penerbit" value="{{ old('penerbit') }}" placeholder="Penerbit..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}" placeholder="2024"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Jumlah Stok</label>
                    <input type="number" name="stok" value="{{ old('stok', 1) }}" required min="1"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none font-bold text-slate-700">
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
                        <i class="fas fa-save mr-2"></i> Simpan Buku Baru
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        const previewContainer = document.getElementById('preview-container');
        const imagePreview = document.getElementById('image-preview');
        const placeholder = document.getElementById('upload-placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection