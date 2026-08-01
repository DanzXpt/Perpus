<x-app-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">Riwayat Transaksi</h2>
            <p class="text-sm text-gray-500">Daftar seluruh transaksi yang berhasil dilakukan oleh kasir.</p>
        </div>
        <a href="{{ route('pos.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition flex items-center gap-2">
            <i class="fa-solid fa-cash-register"></i> Kembali ke POS
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Belanja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tunai / Kembalian</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Rp {{ number_format($trx->cash, 0, ',', '.') }} / <span class="text-green-600 font-semibold">Rp {{ number_format($trx->change_amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('pos.receipt', $trx->id) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md border border-indigo-200 transition">
                                    <i class="fa-solid fa-print mr-1"></i> Cetak Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-400 text-sm py-8">
                                Belum ada riwayat transaksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>