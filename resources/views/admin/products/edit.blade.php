<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                        <select name="category_id"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" style="background-color: #2563eb !important; color: #ffffff !important;"
                            class="px-4 py-2 rounded-md font-medium shadow hover:bg-blue-700">
                            Simpan Produk
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:underline">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>