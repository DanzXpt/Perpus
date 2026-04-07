<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota - PerpusID</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-700 min-h-screen flex items-center justify-center p-4">

    <div class="fixed inset-0 overflow-hidden -z-10">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-blue-100/50 blur-[120px]"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-indigo-100/50 blur-[120px]"></div>
    </div>

    <div class="w-full max-w-[450px]">
        <div class="bg-white/80 backdrop-blur-xl p-8 md:p-12 rounded-[3rem] shadow-2xl shadow-blue-900/5 border border-white relative overflow-hidden">
            
            <i class="fa-solid fa-user-plus absolute -right-6 -top-6 text-[120px] text-slate-50/50 -rotate-12 pointer-events-none"></i>

            <div class="relative">
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-tr from-blue-600 to-indigo-500 rounded-2xl shadow-lg shadow-blue-200 mb-4 rotate-3">
                        <i class="fas fa-book-reader text-white text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Gabung PerpusID</h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Mulai petualangan literasimu hari ini.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-2xl">
                        @foreach ($errors->all() as $error)
                            <p class="text-red-600 text-[11px] font-bold uppercase tracking-tight">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ url('/register') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="space-y-4">
                        <div class="group">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5 ml-1">Nama Lengkap</label>
                            <div class="relative">
                                <i class="fa-solid fa-signature absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="text" name="name" required value="{{ old('name') }}"
                                    class="w-full pl-12 pr-6 py-4 bg-slate-100/50 border border-transparent rounded-2xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-semibold text-slate-700 placeholder:text-slate-300" 
                                    placeholder="Ahdan Muzaki">
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5 ml-1">Email</label>
                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="email" name="email" required value="{{ old('email') }}"
                                    class="w-full pl-12 pr-6 py-4 bg-slate-100/50 border border-transparent rounded-2xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-semibold text-slate-700 placeholder:text-slate-300" 
                                    placeholder="ahdan@example.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="group">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5 ml-1">Password</label>
                                <input type="password" name="password" required 
                                    class="w-full px-6 py-4 bg-slate-100/50 border border-transparent rounded-2xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-semibold text-slate-700" 
                                    placeholder="••••••••">
                            </div>
                            <div class="group">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5 ml-1">Konfirmasi</label>
                                <input type="password" name="password_confirmation" required 
                                    class="w-full px-6 py-4 bg-slate-100/50 border border-transparent rounded-2xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-semibold text-slate-700" 
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl font-extrabold uppercase tracking-widest shadow-xl shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-0.5 active:scale-[0.98] transition-all mt-4 flex items-center justify-center gap-3">
                        <span>Daftar Sekarang</span>
                        <i class="fa-solid fa-arrow-right-long text-xs"></i>
                    </button>
                </form>

                <div class="mt-10 pt-8 border-t border-slate-100 text-center">
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-[0.1em]">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-indigo-600 transition-colors ml-1 underline decoration-2 underline-offset-3">Masuk</a>
                    </p>
                </div>
            </div>
        </div>

        <p class="text-center mt-8 text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em]">
            &copy; {{ date('Y') }} PerpusID &bull; Dev by Ahdan
        </p>
    </div>

</body>
</html>