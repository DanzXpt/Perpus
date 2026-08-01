<div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-4 rounded-lg shadow-sm mb-6">
    <!-- Sapaan / Selamat Datang di Kiri -->
    <div>
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Selamat Datang, {{ Auth::user()->name }} 👋
        </h2>
        <p class="text-xs text-gray-500 mt-0.5">Sistem Point of Sales (POS) - Kasir Aktif</p>
    </div>

    <!-- Menu Navigasi di Kanan (Tombol Berubah Biru Jika Sedang Aktif) -->
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('pos.index') }}" class="px-4 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('pos.index') ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Kasir (POS)
        </a>
        <a href="{{ route('pos.history') }}" class="px-4 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('pos.history') ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Riwayat
        </a>
        <a href="{{ route('pos.report') }}" class="px-4 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('pos.report') ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
            Laporan
        </a>
    </div>
</div>