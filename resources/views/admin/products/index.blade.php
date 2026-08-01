<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('admin.products.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow-md inline-block">
                        + Tambah Produk Baru
                    </a>
                    <a href="{{ route('pos.index') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow-md inline-block">
                        Kembali ke Halaman POS
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Produk</th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Harga</th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stok</th>
                            <th
                                class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">

                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                        class="inline-flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow transition">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- Tombol Hapus (Wajib menggunakan form dengan method DELETE) -->
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow transition">
                                            <i class="fas fa-trash-can"></i> Hapus
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>