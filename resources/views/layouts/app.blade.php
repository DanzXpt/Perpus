<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel POS') }}</title>

    <!-- FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Pemaksa Dark Mode untuk Card Produk & Konten di dalam Main */
        html.dark main .bg-white {
            background-color: #1f2937 !important; /* bg-gray-800 */
            color: #f3f4f6 !important; /* text-gray-100 */
            border-color: #374151 !important; /* border-gray-700 */
        }
        html.dark main .text-gray-700, 
        html.dark main .text-gray-800, 
        html.dark main .text-gray-900 {
            color: #f3f4f6 !important;
        }
        html.dark main .text-gray-500, 
        html.dark main .text-gray-600 {
            color: #9ca3af !important;
        }
    </style>

    <!-- Scripts Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased transition-colors duration-300"
    x-data="{ 
          sidebarOpen: false, 
          darkMode: localStorage.getItem('darkMode') === 'true' 
      }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <div style="display: flex; min-height: 100vh;">

        <!-- SIDEBAR KIRI -->
        <aside
            style="width: 260px; background-color: #111827; color: #d1d5db; display: flex; flex-direction: column; justify-content: space-between; flex-shrink: 0; min-height: 100vh;">
            <div>
                <!-- Logo / Brand -->
                <div
                    style="height: 64px; display: flex; align-items: center; padding: 0 24px; background-color: #030712; color: #ffffff; font-weight: bold; font-size: 16px; gap: 10px;">
                    <i class="fa-solid fa-cash-register" style="color: #3b82f6;"></i> POS SYSTEM
                </div>

                <!-- Menu Navigasi -->
                <nav class="space-y-2 p-4 font-sans text-sm">

                    <!-- Menu Kasir / Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors duration-150 
       {{ request()->routeIs('dashboard')
    ? 'bg-blue-600 text-white shadow'
    : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                        <i class="fas fa-desktop w-5 text-center"></i>
                        Kasir / Dashboard
                    </a>

                    <!-- Menu Manajemen Produk -->
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors duration-150 
       {{ request()->routeIs('admin.products.*')
    ? 'bg-blue-600 text-white shadow'
    : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                        <i class="fas fa-boxes-stacked w-5 text-center"></i>
                        Manajemen Produk
                    </a>

                    <!-- Menu Riwayat Transaksi -->
                    <a href="{{ route('pos.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors duration-150 
       {{ request()->routeIs('pos.history')
    ? 'bg-blue-600 text-white shadow'
    : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                        <i class="fas fa-clock-rotate-left w-5 text-center"></i>
                        Riwayat Transaksi
                    </a>

                    <!-- Menu Laporan Penjualan -->
                    <a href="{{ route('pos.report') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium transition-colors duration-150 
       {{ request()->routeIs('pos.report')
    ? 'bg-blue-600 text-white shadow'
    : 'text-gray-200 hover:bg-gray-800 hover:text-white' }}">
                        <i class="fas fa-chart-line w-5 text-center"></i>
                        Laporan Penjualan
                    </a>

                </nav>
            </div>

            <!-- Bagian Bawah Sidebar (Logout) -->
            <div style="padding: 16px; background-color: #030712; border-top: 1px solid #1f2937;">
                <div
                    style="font-size: 12px; color: #9ca3af; margin-bottom: 8px; padding-left: 4px; overflow: hidden; text-overflow: ellipsis;">
                    {{ Auth::user()->name ?? 'Administrator' }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; background-color: rgba(239, 68, 68, 0.2); color: #f87171; border: none; padding: 8px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer;">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar (Logout)
                    </button>
                </form>
            </div>
        </aside>

        <!-- KONTEN UTAMA KANAN -->
        <div style="flex: 1; display: flex; flex-direction: column; min-width: 0;">
            <!-- Topbar -->
            <header
                class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-4 sm:px-8 z-30 sticky top-0 transition-colors duration-300">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true"
                        class="md:hidden text-gray-600 dark:text-gray-300 hover:text-gray-900 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-base sm:text-lg font-bold text-gray-800 dark:text-white">Panel Aplikasi Kasir</h1>
                </div>

                <!-- Bagian Kanan Header: Waktu Realtime & Tombol Dark/Light Mode -->
                <div class="flex items-center gap-4">
                    <!-- Tampilan Waktu Realtime -->
                    <div class="text-right hidden sm:block" x-data="{ 
                        time: '', 
                        date: '',
                        updateTime() {
                            const now = new Date();
                            this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                            this.date = now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
                        }
                    }" x-init="updateTime(); setInterval(() => updateTime(), 1000)">
                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200" x-text="time"></p>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400" x-text="date"></p>
                    </div>

                    <!-- Tombol Toggle Dark / Light Mode -->
                    <button @click="darkMode = !darkMode"
                        class="w-9 h-9 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-yellow-400 rounded-full flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition focus:outline-none"
                        title="Ubah Tema">
                        <i class="fas" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon text-gray-600'"></i>
                    </button>
                </div>
            </header>

            <!-- Isi Halaman (Slot) -->
            <main class="dark:bg-gray-900 transition-colors duration-300" style="flex: 1; padding: 24px; overflow-y: auto;">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>

</html>