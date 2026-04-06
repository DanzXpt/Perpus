@extends('layouts.app')

@section('content')
    <div class="p-8 bg-slate-50 min-h-screen">
        {{-- Header --}}
        <div class="mb-8 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic">Profil Saya</h1>
                <p class="text-slate-500 text-sm font-medium mt-1">Kelola informasi akun dan keamanan Anda</p>
            </div>
            <div class="text-right hidden md:block">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Terakhir Login</span>
                <span class="text-sm font-bold text-slate-700">{{ now()->format('d M Y, H:i') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Sidebar Profil (Info Ringkas) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 text-center">
                    <div class="relative inline-block mb-6">
                        <div
                            class="w-32 h-32 bg-gradient-to-tr from-blue-600 to-indigo-500 rounded-full flex items-center justify-center text-white font-black text-5xl shadow-2xl shadow-blue-200 border-4 border-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div
                            class="absolute bottom-1 right-1 w-8 h-8 bg-emerald-500 border-4 border-white rounded-full shadow-sm">
                        </div>
                    </div>

                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ Auth::user()->name }}</h3>
                    <p class="text-slate-400 font-bold text-sm mb-6">{{ Auth::user()->email }}</p>

                    <div class="flex flex-col gap-2">
                        <div
                            class="px-4 py-3 bg-blue-50 text-blue-600 rounded-2xl text-xs font-black uppercase tracking-widest border border-blue-100 flex items-center justify-center gap-2">
                            <i class="fas fa-user-shield"></i> {{ ucfirst(Auth::user()->role) }}
                        </div>
                        <div
                            class="px-4 py-3 bg-slate-50 text-slate-500 rounded-2xl text-xs font-black uppercase tracking-widest border border-slate-100 flex items-center justify-center gap-2">
                            <i class="fas fa-calendar-alt"></i> Bergabung:
                            {{ Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Utama --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Form Informasi Profil --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <h2 class="font-black text-slate-800 text-lg tracking-tight italic uppercase">Informasi Dasar</h2>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" class="p-8">
                        @csrf @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                    <i class="fas fa-user text-[8px]"></i> Nama Lengkap <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}"
                                    class="w-full mt-2 px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-blue-500 focus:bg-white transition-all rounded-2xl focus:ring-0 font-bold text-slate-700">
                            </div>

                            <div>
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                    <i class="fas fa-envelope text-[8px]"></i> Email <span class="text-rose-500">*</span>
                                </label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}"
                                    class="w-full mt-2 px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-blue-500 focus:bg-white transition-all rounded-2xl focus:ring-0 font-bold text-slate-700">
                            </div>

                            <div>
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                    <i class="fas fa-phone text-[8px]"></i> Nomor Telepon
                                </label>
                                <input type="text" name="no_telp" value="{{ Auth::user()->no_telp }}"
                                    class="w-full mt-2 px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-blue-500 focus:bg-white transition-all rounded-2xl focus:ring-0 font-bold text-slate-700">
                            </div>

                            <div class="col-span-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-[8px]"></i> Alamat Lengkap
                                </label>
                                <textarea name="alamat" rows="3"
                                    class="w-full mt-2 px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-blue-500 focus:bg-white transition-all rounded-2xl focus:ring-0 font-bold text-slate-700">{{ Auth::user()->alamat }}</textarea>
                            </div>
                        </div>

                        <button type="submit"
                            class="mt-8 px-8 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center gap-3">
                            <i class="fas fa-save"></i> Perbarui Profil
                        </button>
                    </form>
                </div>

                {{-- Form Ganti Password --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-slate-50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h2 class="font-black text-slate-800 text-lg tracking-tight italic uppercase">Keamanan Password</h2>
                    </div>

                    <form action="{{ route('profile.password') }}" method="POST" class="p-8">
                        @csrf @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                            <div class="col-span-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                    <i class="fas fa-unlock text-[8px]"></i> Password Saat Ini <span
                                        class="text-rose-500">*</span>
                                </label>
                                <input type="password" name="current_password"
                                    class="w-full mt-2 px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-amber-500 focus:bg-white transition-all rounded-2xl focus:ring-0 font-bold text-slate-700">
                            </div>

                            <div>
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                    <i class="fas fa-key text-[8px]"></i> Password Baru <span class="text-rose-500">*</span>
                                </label>
                                <input type="password" name="password" placeholder="Min. 6 karakter"
                                    class="w-full mt-2 px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-amber-500 focus:bg-white transition-all rounded-2xl focus:ring-0 font-bold text-slate-700">
                            </div>

                            <div>
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 flex items-center gap-1">
                                    <i class="fas fa-redo text-[8px]"></i> Konfirmasi Baru <span
                                        class="text-rose-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation"
                                    class="w-full mt-2 px-6 py-4 bg-slate-50 border-2 border-transparent focus:border-amber-500 focus:bg-white transition-all rounded-2xl focus:ring-0 font-bold text-slate-700">
                            </div>
                        </div>

                        <button type="submit"
                            class="mt-8 px-8 py-4 bg-amber-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-amber-600 transition-all shadow-lg shadow-amber-100 flex items-center gap-3">
                            <i class="fas fa-lock"></i> Update Password
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection