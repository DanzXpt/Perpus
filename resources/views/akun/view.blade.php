@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 p-4">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Detail Profil Akun</h1>
            <p class="text-sm text-slate-500">Informasi lengkap mengenai kredensial dan data diri pengguna.</p>
        </div>
        <div class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-2xl text-[10px] font-bold uppercase border border-indigo-100 tracking-widest">
            ID: #{{ $user->id }}
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <div class="space-y-6">
                <h3 class="text-xs font-bold text-indigo-500 uppercase tracking-widest border-b border-slate-50 pb-2">Informasi Akun</h3>
                
                <div class="flex flex-col space-y-1">
                    <span class="text-xs text-slate-400 font-medium italic">Nama Lengkap</span>
                    <span class="text-lg font-bold text-slate-800">{{ $user->name }}</span>
                </div>

                <div class="flex flex-col space-y-1">
                    <span class="text-xs text-slate-400 font-medium italic">Alamat Email</span>
                    <span class="text-sm font-semibold text-slate-700">{{ $user->email }}</span>
                </div>

                <div class="flex flex-col space-y-1">
                    <span class="text-xs text-slate-400 font-medium italic">Level Akses</span>
                    <div>
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase tracking-tighter">
                            {{ ucfirst($user->level) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="space-y-6 bg-slate-50/50 p-6 rounded-3xl border border-slate-100">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2">Data Spesifik</h3>

                @if($user->level == 'anggota' && $user->anggota)
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-slate-400 uppercase font-bold">NIS</span>
                            <span class="text-sm font-bold text-slate-700">{{ $user->anggota->nis }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-slate-400 uppercase font-bold">Kelas</span>
                            <span class="text-sm font-bold text-slate-700">{{ $user->anggota->kelas }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col pt-2">
                        <span class="text-[10px] text-slate-400 uppercase font-bold">Alamat</span>
                        <span class="text-sm text-slate-600 leading-relaxed">{{ $user->anggota->alamat ?? '-' }}</span>
                    </div>
                @endif

                @if($user->level == 'petugas' && $user->petugas)
                    <div class="flex flex-col space-y-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-slate-400 uppercase font-bold">NIP Petugas</span>
                            <span class="text-sm font-bold text-slate-700">{{ $user->petugas->nip_petugas ?? '-' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-slate-400 uppercase font-bold">No. HP / WhatsApp</span>
                            <span class="text-sm font-bold text-emerald-600">{{ $user->petugas->no_hp ?? '-' }}</span>
                        </div>
                    </div>
                @endif

                @if($user->level == 'kepala' && $user->kepala)
                    <div class="flex flex-col">
                        <span class="text-[10px] text-slate-400 uppercase font-bold">NIP Kepala</span>
                        <span class="text-sm font-bold text-slate-700">{{ $user->kepala->nip_kepala ?? '-' }}</span>
                    </div>
                @endif

                <div class="pt-4 border-t border-slate-200 flex justify-between">
                    <div class="text-[10px] text-slate-400">
                        Dibuat: <span class="text-slate-600 font-medium">{{ optional($user->created_at)->format('d M Y') }}</span>
                    </div>
                    <div class="text-[10px] text-slate-400 text-right">
                        Update: <span class="text-slate-600 font-medium">{{ optional($user->updated_at)->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t border-slate-100 flex flex-wrap gap-3">
            <a href="{{ route('admin.akun.edit', $user->id) }}" 
               class="px-6 py-3 bg-amber-500 text-white rounded-2xl text-xs font-bold hover:bg-amber-600 transition shadow-lg shadow-amber-200 flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit Akun
            </a>

            <form action="{{ route('admin.akun.destroy', $user->id) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Hapus akun ini? Data terkait mungkin akan ikut terhapus.')"
                        class="px-6 py-3 bg-rose-50 text-rose-500 border border-rose-100 rounded-2xl text-xs font-bold hover:bg-rose-100 transition flex items-center gap-2">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>

            <a href="{{ route('admin.akun.index') }}" 
               class="px-6 py-3 bg-slate-800 text-white rounded-2xl text-xs font-bold hover:bg-slate-900 transition ml-auto">
                Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection