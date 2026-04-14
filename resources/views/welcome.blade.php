<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - PerpusDigital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body
    class="bg-gradient-to-r from-indigo-600 to-cyan-600 bg-slate-50 flex items-center justify-center min-h-screen p-6">

    <div
        class="relative text-center bg-slate-800 px-12 md:px-24 py-20 rounded-[3rem] shadow-md shadow-slate-100 overflow-hidden w-full max-w-2xl">

        {{-- icon besar --}}
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <i class="fa-solid fa-book-bookmark text-[300px] text-white opacity-[0.03] -rotate-12"></i>
        </div>

        {{-- icon kecil --}}
        <div class="relative z-10">
            <div class="w-16 h-16 bg-indigo-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-book-open text-2xl text-indigo-400"></i>
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-white mb-2 tracking-tighter uppercase">
                Perpus<span class="text-indigo-400">Digital</span>
            </h1>

            <p class="text-slate-400 mb-10 font-medium tracking-wide uppercase text-[10px]">
                Sistem Manajemen Perpustakaan Modern
            </p>

            <div class="flex flex-col md:flex-row items-center justify-center gap-4">
                <a href="/login"
                    class="w-full md:w-auto bg-indigo-600 text-white px-10 py-4 rounded-2xl font-bold shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 hover:-translate-y-1 transition-all">
                    Masuk ke Aplikasi
                </a>
                <a href="/register"
                    class="w-full md:w-auto bg-slate-700 text-slate-300 px-10 py-4 rounded-2xl font-bold hover:bg-slate-600 hover:-translate-y-1 transition-all border border-slate-600">
                    Daftar Akun
                </a>
            </div>
        </div>

    </div>

    <p class="absolute bottom-10 text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em]">
        &copy; {{ date('Y') }} DannzMusk
    </p>

</body>

</html>

