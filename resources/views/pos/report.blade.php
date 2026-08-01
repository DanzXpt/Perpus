<x-app-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">Laporan Penjualan</h2>
            <p class="text-sm text-gray-500">Rekapitulasi pendapatan dan statistik transaksi toko.</p>
        </div>
        <a href="{{ route('pos.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition flex items-center gap-2">
            <i class="fa-solid fa-cash-register"></i> Kembali ke POS
        </a>
    </div>

    <!-- Form Filter Tanggal -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('pos.report') }}" class="flex flex-col sm:flex-row gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Kartu Statistik Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-blue-50 text-blue-600 p-3 rounded-full text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Transaksi Berhasil</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalTransactions }} Transaksi</h3>
            </div>
            <div class="bg-green-50 text-green-600 p-3 rounded-full text-xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Laporan -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Rincian Transaksi Periode Ini</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $trx)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $trx->trx_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $trx->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $trx->user->name ?? 'Admin' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-400 text-sm py-8">
                                Tidak ada data penjualan pada rentang tanggal tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>