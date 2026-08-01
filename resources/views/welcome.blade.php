<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel POS') }} - Point of Sales</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-950 text-gray-100 min-h-screen flex flex-col justify-between">

    <!-- Navbar Atas -->
    <header class="w-full border-b border-gray-800 bg-gray-900/50 backdrop-blur-md fixed top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-600 text-white p-2 rounded-lg">
                    <i class="fa-solid fa-cash-register"></i>
                </div>
                <span class="font-bold text-lg tracking-wider text-white">WEB KASIR POS</span>
            </div>
            
            <div class="flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                            <i class="fa-solid fa-gauge mr-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 py-2 text-sm font-medium transition">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition shadow">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section / Konten Utama -->
    <main class="flex-1 flex items-center justify-center pt-24 pb-12 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-blue-950 border border-blue-800 text-blue-400 px-3 py-1 rounded-full text-xs font-semibold mb-6">
                <i class="fa-solid fa-bolt"></i> Sistem Point of Sales Profesional
            </div>
            <h1 class="text-4xl sm:text-6xl font-extrabold text-white tracking-tight leading-tight mb-6">
                Solusi Kasir & Manajemen Toko <span class="text-blue-500">Modern</span>
            </h1>
            <p class="text-gray-400 text-base sm:text-lg max-w-2xl mx-auto mb-10">
                Kelola transaksi penjualan, stok produk secara otomatis, cetak struk thermal, hingga rekapitulasi laporan keuangan dengan cepat dan akurat.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Masuk ke Dashboard Kasir
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk (Login)
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-gray-800 hover:bg-gray-700 text-gray-200 border border-gray-700 px-8 py-3 rounded-lg font-medium transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-user-plus"></i> Buat Akun Baru
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-900 bg-gray-950 py-6 text-center text-xs text-gray-500">
        <p>Copyright &copy; 2026 <strong>Ahdan Muzaki</strong>. All rights reserved.</p>
    </footer>

</body>
</html>