<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Perpustakaan Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        {{-- Logo atau Nama Perpus --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-200 mb-4">
                <i class="fas fa-book-reader text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Selamat Datang</h1>
            <p class="text-slate-500 text-sm mt-1">Silakan masuk untuk akses perpustakaan</p>
        </div>

        {{-- Box Login --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                {{-- Input Email --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email / Username</label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" name="email" required placeholder="nama@email.com"
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm">
                    </div>
                </div>

                {{-- Input Password --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all text-sm">
                    </div>
                </div>

                {{-- Tombol Login --}}
                <div class="pt-2">
                    <button type="submit" 
                        class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 shadow-xl shadow-indigo-100 transition-all active:scale-[0.98]">
                        Masuk Sekarang
                    </button>
                </div>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center mt-8 text-sm text-slate-400">
            &copy; 2026 Perpustakaan Digital - SMK RPL
        </p>
    </div>

</body>
</html>