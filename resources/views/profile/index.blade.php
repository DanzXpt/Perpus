@extends('layouts.app') {{-- Pastikan ini nama layout utamamu --}}

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white rounded-[3rem] shadow-2xl shadow-indigo-100 overflow-hidden border border-slate-100">
        {{-- Header Profil --}}
        <div class="bg-slate-900 p-12 text-white relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-indigo-600/20 rounded-full blur-3xl"></div>
            
            <div class="relative flex items-center gap-8">
                <div class="w-24 h-24 bg-indigo-500 rounded-[2rem] flex items-center justify-center text-4xl font-black shadow-xl border-4 border-slate-800">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-3xl font-black uppercase tracking-tighter italic italic">{{ Auth::user()->name }}</h1>
                    <span class="bg-indigo-500/20 text-indigo-400 px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest border border-indigo-500/30">
                        Level: {{ Auth::user()->level }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Detail Informasi --}}
        <div class="p-12 space-y-8">
            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Username</p>
                    <p class="text-slate-800 font-bold text-lg">{{ Auth::user()->username ?? 'Belum diset' }}</p>
                </div>
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email Terdaftar</p>
                    <p class="text-slate-800 font-bold text-lg">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex gap-4">
                <button class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                    Edit Profil
                </button>
                <button class="bg-slate-100 text-slate-600 px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-slate-200 transition-all">
                    Ganti Password
                </button>
            </div>
        </div>
    </div>
</div>
@endsection