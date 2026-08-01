<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Produk Baru</h2>

        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nama Produk</label>
                <input type="text" name="name" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                <select name="category_id" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Harga (Rp)</label>
                <input type="number" name="price" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Stok</label>
                <input type="number" name="stock" class="w-full border border-gray-300 p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 font-medium">
                    Simpan Produk
                </button>
                <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:underline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</body>
</html>