@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Akun Baru</h1>
            <p class="text-sm text-slate-500">Daftarkan pengguna baru ke dalam sistem perpustakaan.</p>
        </div>
        <a href="{{ route('admin.akun.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-8">
        <form action="{{ route('admin.akun.store') }}" method="POST" class="space-y-8">
            @csrf

            <div class="space-y-6">
                <h3 class="text-xs font-bold text-indigo-500 uppercase tracking-widest border-b border-slate-50 pb-2">Kredensial Login</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Pilih Level Akses *</label>
                        <select name="level" id="level" required
                            class="w-full px-4 py-3 rounded-2xl border @error('level') border-rose-500 @else border-slate-200 @enderror focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-semibold text-slate-700">
                            <option value="">-- Pilih Level --</option>
                            <option value="anggota" {{ old('level') == 'anggota' ? 'selected' : '' }}>Anggota (Siswa)</option>
                            <option value="petugas" {{ old('level') == 'petugas' ? 'selected' : '' }}>Petugas Perpustakaan</option>
                            <option value="kepala" {{ old('level') == 'kepala' ? 'selected' : '' }}>Kepala Perpustakaan</option>
                        </select>
                        @error('level') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 rounded-2xl border @error('name') border-rose-500 @else border-slate-200 @enderror focus:border-indigo-500 outline-none text-sm font-medium">
                        @error('name') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Alamat Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 rounded-2xl border @error('email') border-rose-500 @else border-slate-200 @enderror focus:border-indigo-500 outline-none text-sm font-medium">
                        @error('email') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Password *</label>
                        <input type="password" name="password" 
                            class="w-full px-4 py-3 rounded-2xl border @error('password') border-rose-500 @else border-slate-200 @enderror focus:border-indigo-500 outline-none text-sm">
                        @error('password') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Konfirmasi Password *</label>
                        <input type="password" name="password_confirmation" 
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-indigo-500 outline-none text-sm">
                    </div>
                </div>
            </div>

            {{-- Container Dinamis --}}
            <div id="dynamicFieldsContainer" class="hidden space-y-6 bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100 transition-all duration-300">
                
                {{-- Field Anggota --}}
                <div id="fieldAnggota" class="hidden space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2">Detail Data Anggota</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">NIS *</label>
                            <input type="text" name="nis" value="{{ old('nis') }}" 
                                class="w-full px-4 py-2.5 rounded-xl border @error('nis') border-rose-500 @else border-slate-200 @enderror focus:border-indigo-500 outline-none text-sm font-bold">
                            @error('nis') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Kelas *</label>
                            <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="12 RPL 1" 
                                class="w-full px-4 py-2.5 rounded-xl border @error('kelas') border-rose-500 @else border-slate-200 @enderror focus:border-indigo-500 outline-none text-sm font-bold">
                            @error('kelas') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm">{{ old('alamat') }}</textarea>
                    </div>
                </div>

                {{-- Field Petugas --}}
                <div id="fieldPetugas" class="hidden space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2">Detail Data Petugas</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">NIP Petugas</label>
                            <input type="text" name="nip_petugas" value="{{ old('nip_petugas') }}" 
                                class="w-full px-4 py-2.5 rounded-xl border @error('nip_petugas') border-rose-500 @else border-slate-200 @enderror focus:border-indigo-500 outline-none text-sm font-bold">
                            @error('nip_petugas') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold text-emerald-600">
                        </div>
                    </div>
                </div>

                {{-- Field Kepala --}}
                <div id="fieldKepala" class="hidden space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2">Detail Data Kepala</h3>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">NIP Kepala Perpus</label>
                        <input type="text" name="nip_kepala" value="{{ old('nip_kepala') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border @error('nip_kepala') border-rose-500 @else border-slate-200 @enderror focus:border-indigo-500 outline-none text-sm font-bold">
                        @error('nip_kepala') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('admin.akun.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Batalkan</a>
                <button type="submit" class="px-10 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                    Daftarkan Akun
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const levelSelect = document.getElementById('level');
    const container = document.getElementById('dynamicFieldsContainer');
    const fAnggota = document.getElementById('fieldAnggota');
    const fPetugas = document.getElementById('fieldPetugas');
    const fKepala = document.getElementById('fieldKepala');

    function updateFields() {
        const val = levelSelect.value;
        
        // Reset state
        container.classList.add('hidden');
        fAnggota.classList.add('hidden');
        fPetugas.classList.add('hidden');
        fKepala.classList.add('hidden');

        if (val) {
            container.classList.remove('hidden');
            if (val === 'anggota') fAnggota.classList.remove('hidden');
            if (val === 'petugas') fPetugas.classList.remove('hidden');
            if (val === 'kepala') fKepala.classList.remove('hidden');
        }
    }

    levelSelect.addEventListener('change', updateFields);

    // Jalankan saat load (penting kalau ada error validation agar input tetap muncul)
    window.addEventListener('DOMContentLoaded', updateFields);
</script>
@endpush