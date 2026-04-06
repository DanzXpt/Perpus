<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota - PerpusID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<style>
    body { font-family: 'Inter', sans-serif; }
</style>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4 text-slate-700">

    <div class="w-full max-w-[420px]">
        {{-- Main Card --}}
        <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden">
            {{-- Variasi Dekorasi --}}
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full opacity-50"></div>
            
            <div class="relative">
                <h2 class="text-2xl font-bold text-slate-800 mb-1 text-center tracking-tight">Buat Akun</h2>
                <p class="text-slate-500 text-sm mb-8 font-medium text-center">Lengkapi data diri kamu di bawah ini.</p>

                {{-- Alert Error --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-xl">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-600 text-[11px] font-semibold uppercase tracking-tight">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ url('/register') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    {{-- Input Nama --}}
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <div class="relative mt-1 group">
                            <i class="fa-solid fa-user absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-sm"></i>
                            <input type="text" name="name" required value="{{ old('name') }}"
                                class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white outline-none transition-all font-medium text-slate-700 placeholder:text-slate-300" 
                                placeholder="Ahdan Muzaki">
                        </div>
                    </div>

                    {{-- Input Email --}}
                    <div>
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Email</label>
                        <div class="relative mt-1 group">
                            <i class="fa-solid fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors text-sm"></i>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white outline-none transition-all font-medium text-slate-700 placeholder:text-slate-300" 
                                placeholder="name@email.com">
                        </div>
                    </div>

                    {{-- Input Password --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Password</label>
                            <input type="password" name="password" required 
                                class="w-full mt-1 px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white outline-none transition-all font-medium text-slate-700" 
                                placeholder="••••••••">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Konfirmasi</label>
                            <input type="password" name="password_confirmation" required 
                                class="w-full mt-1 px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white outline-none transition-all font-medium text-slate-700" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 active:scale-[0.98] transition-all mt-4 flex items-center justify-center gap-2">
                        <span>Daftar Akun</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

                {{-- Footer Link --}}
                <div class="mt-8 pt-6 border-t border-slate-50 text-center">
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline ml-1">Login Sekarang</a>
                    </p>
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <p class="text-center mt-8 text-slate-400 text-[10px] font-semibold uppercase tracking-widest">
            &copy; {{ date('Y') }} Perpustakaan Digital
        </p>
    </div>

</body>
</html>