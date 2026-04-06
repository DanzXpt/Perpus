@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Akun</h1>
            <p class="text-sm text-slate-500 italic">Mengubah data profil untuk: <span class="font-bold text-indigo-600">{{ $user->name }}</span></p>
        </div>
        <div class="px-4 py-2 bg-slate-100 text-slate-600 rounded-2xl text-[10px] font-bold uppercase tracking-widest border border-slate-200">
            role: {{ $user->role }}
        </div>
    </div>

    {{-- Alert Error --}}
    @if ($errors->any())
    <div class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-2xl mb-6 text-sm font-medium">
        <p class="font-bold mb-1 italic text-xs uppercase tracking-widest">Ada masalah pada input:</p>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-8">
        <form action="{{ route('kepala.akun.update', $user->id) }}" method="POST" class="space-y-8">
            @method('PUT')
            @csrf

            {{-- Kredensial Utama --}}
            <div class="space-y-5">
                <h3 class="text-xs font-bold text-indigo-500 uppercase tracking-widest border-b border-slate-50 pb-2">Kredensial Utama</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Nama Lengkap *</label>
                        <input type="text" name="name" 
                            class="w-full px-4 py-3 rounded-2xl border @error('name') border-rose-500 @else border-slate-200 @enderror focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-medium"
                            value="{{ old('name', $user->name) }}">
                        @error('name') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700 ml-1">Alamat Email *</label>
                        <input type="email" name="email" 
                            class="w-full px-4 py-3 rounded-2xl border @error('email') border-rose-500 @else border-slate-200 @enderror focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-medium"
                            value="{{ old('email', $user->email) }}">
                        @error('email') <p class="text-[10px] text-rose-500 font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Data Spesifik role --}}
            <div class="space-y-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50 pb-2">Detail Identitas</h3>

                {{-- FORM ANGGOTA --}}
                @if($user->role == 'anggota')
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1 tracking-tight">NIS *</label>
                            <input type="text" name="nis" 
                                class="w-full px-4 py-2.5 rounded-xl border @error('nis') border-rose-500 @else border-slate-200 @enderror focus:border-indigo-500 outline-none text-sm font-bold"
                                value="{{ old('nis', $user->anggota?->nis) }}">
                            @error('nis') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-slate-500 uppercase ml-1 tracking-tight">Kelas *</label>
                            <input type="text" name="kelas" 
                                class="w-full px-4 py-2.5 rounded-xl border @error('kelas') border-rose-500 @else border-slate-200 @enderror focus:border-indigo-500 outline-none text-sm font-bold"
                                value="{{ old('kelas', $user->anggota->kelas) }}">
                            @error('kelas') <p class="text-[10px] text-rose-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1 tracking-tight">Alamat</label>
                        <textarea name="alamat" rows="2" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm">{{ old('alamat', $user->anggota?->alamat) }}</textarea>
                    </div>
                </div>
                @endif

                {{-- FORM PETUGAS --}}
                @if($user->role == 'petugas')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1 tracking-tight">NIP Petugas</label>
                        <input type="text" name="nip_petugas" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold"
                            value="{{ old('nip_petugas', $user->petugas->nip) }}">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-slate-500 uppercase ml-1 tracking-tight">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold text-emerald-600"
                            value="{{ old('no_hp', $user->petugas->no_hp) }}">
                    </div>
                </div>
                @endif

                {{-- FORM KEPALA --}}
                @if($user->role == 'kepala')
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase ml-1 tracking-tight">NIP Kepala Perpus</label>
                    <input type="text" name="nip" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 outline-none text-sm font-bold"
                        value="{{ old('nip_kepala', $user->kepala->nip) }}">
                </div>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                <a href="{{ route('kepala.akun.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Batalkan
                </a>
                <button type="submit" 
                    class="px-8 py-3 bg-indigo-600 text-white rounded-2xl text-sm font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection