@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 pb-20">
    
    <div class="flex items-center justify-between py-6 border-b border-dashed border-gray-200 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Profil Saya</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola informasi akun Anda</p>
        </div>
        <div class="flex items-center gap-4">
            <p class="text-sm text-gray-500 font-medium">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white text-lg font-black shadow-lg">
                {{ substr($user->name, 0, 1) }}
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center gap-3 font-bold animate-pulse">
        <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl font-bold">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-exclamation-circle text-lg"></i> 
            <span>Terjadi Kesalahan:</span>
        </div>
        <ul class="list-disc list-inside text-xs ml-5 font-medium">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <form action="{{ route('profile.update') }}" method="POST" class="bg-white p-8 sm:p-10 rounded-3xl shadow-sm border border-slate-100 h-full">
                @csrf
                <div class="flex items-center gap-6 mb-10 pb-8 border-b border-gray-100">
                    <div class="w-24 h-24 rounded-full bg-indigo-600 flex items-center justify-center text-white text-5xl font-black shadow-2xl shadow-indigo-200">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $user->name }}</h3>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.2em] 
                                {{ $user->level == 'anggota' ? 'bg-indigo-100 text-indigo-700' : ($user->level == 'petugas' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                                {{ $user->level }}
                            </span>
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                            <span class="text-[10px] font-bold text-green-600 uppercase tracking-widest">Aktif</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="text-sm font-bold text-slate-600 ml-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ $user->name }}" 
                            class="w-full bg-slate-50 border-gray-100 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:ring-2 focus:ring-indigo-200 transition-all mt-1.5 shadow-inner">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-bold text-slate-600 ml-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ $user->email }}" 
                                class="w-full bg-slate-50 border-gray-100 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:ring-2 focus:ring-indigo-200 transition-all mt-1.5 shadow-inner">
                        </div>
                        
                        <div>
                            <label class="text-sm font-bold text-slate-600 ml-1">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" value="{{ $user->petugas?->no_hp ?? $user->anggota?->no_hp ?? '' }}" 
                                class="w-full bg-slate-50 border-gray-100 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:ring-2 focus:ring-indigo-200 transition-all mt-1.5 shadow-inner" placeholder="Contoh: 08123...">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-600 ml-1">Alamat</label>
                        <textarea name="alamat" rows="4" 
                            class="w-full bg-slate-50 border-gray-100 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:ring-2 focus:ring-indigo-200 transition-all mt-1.5 shadow-inner">{{ $user->anggota?->alamat ?? '' }}</textarea>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl border border-dashed border-gray-200 mt-8">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Informasi Tambahan (Read Only)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                            @if($user->level == 'anggota')
                                <p class="text-sm text-slate-600">Username: <span class="font-bold text-slate-900 ml-1">{{ $user->email }}</span></p>
                                <p class="text-sm text-slate-600">NIS: <span class="font-bold text-slate-900 ml-1">{{ $user->anggota?->nis ?? '-' }}</span></p>
                                <p class="text-sm text-slate-600 md:col-span-2">Kelas: <span class="font-bold text-slate-900 ml-1">{{ $user->anggota?->kelas ?? '-' }}</span></p>
                            @elseif($user->level == 'petugas')
                                <p class="text-sm text-slate-600">Username: <span class="font-bold text-slate-900 ml-1">{{ $user->email }}</span></p>
                                <p class="text-sm text-slate-600">NIP: <span class="font-bold text-slate-900 ml-1">{{ $user->petugas?->nip_petugas ?? '-' }}</span></p>
                            @endif
                            <p class="text-sm text-slate-600 md:col-span-2 italic">Bergabung sejak: <span class="font-bold text-slate-900 not-italic ml-1">{{ $user->created_at->translatedFormat('d F Y') }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-gray-100">
                    <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-8">
            <form action="{{ route('profile.update.password') }}" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                @csrf
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center text-sm"><i class="fas fa-lock"></i></span>
                    Ganti Password
                </h3>

                <div class="space-y-5">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Password Lama <span class="text-red-500">*</span></label>
                        <input type="password" name="current_password" required
                            class="w-full bg-slate-50 border-none rounded-xl px-5 py-3.5 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 mt-1 shadow-inner">
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Password Baru <span class="text-red-500">*</span></label>
                        <input type="password" name="new_password" required
                            class="w-full bg-slate-50 border-none rounded-xl px-5 py-3.5 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 mt-1 shadow-inner" placeholder="Min. 6 karakter">
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Konfirmasi <span class="text-red-500">*</span></label>
                        <input type="password" name="new_password_confirmation" required
                            class="w-full bg-slate-50 border-none rounded-xl px-5 py-3.5 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 mt-1 shadow-inner">
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-50 text-center">
                    <button type="submit" class="w-full py-3.5 bg-amber-500 text-white rounded-xl font-bold shadow-xl shadow-amber-100 hover:bg-amber-600 transition-all">
                        Ubah Password
                    </button>
                    <p class="text-[10px] text-slate-400 italic mt-3">Gantilah password secara berkala demi keamanan data.</p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection