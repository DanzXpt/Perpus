@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Akun Baru</h1>
            <p class="text-sm text-slate-500">Daftarkan pengguna baru dengan identitas lengkap.</p>
        </div>
        <a href="{{ route('kepala.akun.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-8">
        <form action="{{ route('kepala.akun.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Bagian 1: Akun Login --}}
            <div class="space-y-6">
                <h3 class="text-xs font-bold text-indigo-500 uppercase tracking-widest border-b border-slate-50 pb-2">Kredensial Login</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Pilih role Akses *</label>
                        <select name="role" id="role" required
                            class="w-full px-4 py-3 rounded-2xl border @error('role') border-rose-500 @else border-slate-200 @enderror focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-semibold text-slate-700">
                            <option value="">-- Pilih role --</option>
                            <option value="anggota" {{ old('role') == 'anggota' ? 'selected' : '' }}>Anggota (Siswa)</option>
                            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas Perpustakaan</option>
                            <option value="kepala" {{ old('role') == 'kepala' ? 'selected' : '' }}>Kepala Perpustakaan</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Nama Lengkap *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-medium">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-medium">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Password *</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-indigo-500 outline-none text-sm">
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Konfirmasi Password *</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-indigo-500 outline-none text-sm">
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Detail Identitas --}}
            <div id="dynamicFieldsContainer" class="hidden space-y-6 bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100 transition-all duration-300">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2">Detail Identitas</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Khusus Anggota --}}
                    <div id="fieldAnggota" class="hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">NIS *</label>
                            <input type="text" name="nis" value="{{ old('nis') }}" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Kelas *</label>
                            <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: 12 RPL 1"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold">
                        </div>
                    </div>

                    {{-- Khusus Petugas/Kepala --}}
                    <div id="fieldPegawai" class="hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">NIP *</label>
                            <input type="text" name="nip" value="{{ old('nip') }}" 
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan') }}" placeholder="Pustakawan / Staff"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none text-sm font-bold">
                        </div>
                    </div>

                    {{-- Umum (Muncul di semua role) --}}
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Jenis Kelamin</label>
                        <select name="jk" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none text-sm font-bold">
                            <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">No. HP / WA</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none text-sm font-bold">
                    </div>

                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 outline-none text-sm">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('kepala.akun.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Batalkan</a>
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
    const roleSelect = document.getElementById('role');
    const container = document.getElementById('dynamicFieldsContainer');
    const fAnggota = document.getElementById('fieldAnggota');
    const fPegawai = document.getElementById('fieldPegawai');

    function updateFields() {
        const val = roleSelect.value;
        
        container.classList.add('hidden');
        fAnggota.classList.add('hidden');
        fPegawai.classList.add('hidden');

        if (val) {
            container.classList.remove('hidden');
            if (val === 'anggota') {
                fAnggota.classList.remove('hidden');
                fAnggota.classList.add('grid'); // Pastikan grid jalan
            } else if (val === 'petugas' || val === 'kepala') {
                fPegawai.classList.remove('hidden');
                fPegawai.classList.add('grid');
            }
        }
    }

    roleSelect.addEventListener('change', updateFields);
    window.addEventListener('DOMContentLoaded', updateFields);
</script>
@endpush