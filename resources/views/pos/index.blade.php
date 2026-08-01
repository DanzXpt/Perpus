<x-app-layout>
    <!-- Header Halaman -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">Dashboard & Kasir (POS)</h2>
            <p class="text-sm text-gray-500">Selamat datang kembali, kelola transaksi dan penjualan toko di sini.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('pos.history') }}"
                class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700 transition flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
            </a>
            <a href="{{ route('pos.report') }}"
                class="bg-emerald-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-emerald-700 transition flex items-center gap-2">
                <i class="fa-solid fa-chart-line"></i> Laporan
            </a>
        </div>
    </div>

    <!-- KARTU STATISTIK DASHBOARD -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Produk -->
        <div class="bg-blue-600 text-white rounded-lg shadow-sm p-6 flex items-center justify-between relative overflow-hidden">
            <div>
                <p class="text-3xl font-bold">{{ \App\Models\Product::count() }}</p>
                <p class="text-sm font-medium text-blue-100 mt-1">Jumlah Produk</p>
            </div>
            <div class="text-blue-400 text-5xl opacity-80">
                <i class="fa-solid fa-box-archive"></i>
            </div>
        </div>

        <!-- Card 2: Total Transaksi -->
        <div class="bg-green-600 text-white rounded-lg shadow-sm p-6 flex items-center justify-between relative overflow-hidden">
            <div>
                <p class="text-3xl font-bold">{{ \App\Models\Transaction::count() }}</p>
                <p class="text-sm font-medium text-green-100 mt-1">Total Transaksi</p>
            </div>
            <div class="text-green-400 text-5xl opacity-80">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <!-- Card 3: Pendapatan Hari Ini -->
        <div class="bg-red-600 text-white rounded-lg shadow-sm p-6 flex items-center justify-between relative overflow-hidden sm:col-span-2 lg:col-span-1">
            <div>
                <p class="text-2xl font-bold">
                    Rp {{ number_format(\App\Models\Transaction::whereDate('created_at', today())->sum('total'), 0, ',', '.') }}
                </p>
                <p class="text-sm font-medium text-red-100 mt-1">Pendapatan Hari Ini</p>
            </div>
            <div class="text-red-400 text-5xl opacity-80">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>
    </div>

    <!-- AREA UTAMA KASIR (POS) & ALPINE.JS -->
    <div x-data="posApp()">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Katalog Produk -->
            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-store text-blue-600"></i> Katalog Produk
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($products as $product)
                        <div @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }})"
                            class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 hover:shadow-md transition bg-gray-50 flex flex-col justify-between">
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm line-clamp-2">{{ $product->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">Stok: {{ $product->stock }}</p>
                            </div>
                            <div class="mt-4 pt-2 border-t border-gray-200">
                                <span class="text-blue-600 font-bold text-sm">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Keranjang Belanja -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2 flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-blue-600"></i> Keranjang Belanja
                    </h3>
                    <div class="divide-y max-h-80 overflow-y-auto mb-4 pr-1">
                        <template x-for="(item, index) in cart" :key="item.product_id">
                            <div class="py-3 flex justify-between items-center">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-800" x-text="item.name"></h4>
                                    <p class="text-xs text-gray-500" x-text="'Rp ' + item.price.toLocaleString() + ' x ' + item.qty"></p>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button @click="decreaseQty(index)" class="w-6 h-6 bg-gray-200 hover:bg-gray-300 rounded text-xs font-bold flex items-center justify-center">-</button>
                                    <span class="text-sm font-semibold w-6 text-center" x-text="item.qty"></span>
                                    <button @click="increaseQty(index)" class="w-6 h-6 bg-gray-200 hover:bg-gray-300 rounded text-xs font-bold flex items-center justify-center">+</button>
                                    <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-700 text-xs ml-2">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <template x-if="cart.length === 0">
                            <p class="text-gray-400 text-sm text-center py-8">Keranjang masih kosong</p>
                        </template>
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Uang Tunai (Cash)</label>
                        <input type="number" x-model.number="cash" class="w-full border border-gray-300 rounded-md p-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan jumlah uang...">
                    </div>

                    <div class="border-t pt-4 mb-4 space-y-1">
                        <div class="flex justify-between font-bold text-lg text-gray-800">
                            <span>Total:</span>
                            <span x-text="'Rp ' + totalPrice.toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Kembalian:</span>
                            <span class="font-semibold text-green-600" x-text="'Rp ' + (cash >= totalPrice ? (cash - totalPrice).toLocaleString() : '0')"></span>
                        </div>
                    </div>

                    <button @click="submitCheckout()" :disabled="cart.length === 0 || cash < totalPrice"
                        style="background-color: #2563eb !important; color: #ffffff !important;"
                        class="w-full py-2.5 rounded-md font-medium shadow hover:bg-blue-700 disabled:opacity-50 text-center transition">
                        Bayar / Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Alpine.js -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('posApp', () => ({
                cart: [],
                cash: 0,
                addToCart(id, name, price, stock) {
                    let existingItem = this.cart.find(item => item.product_id === id);
                    if (existingItem) {
                        if (existingItem.qty < stock) {
                            existingItem.qty++;
                        } else {
                            alert('Stok produk tidak mencukupi!');
                        }
                    } else {
                        this.cart.push({
                            product_id: id,
                            name: name,
                            price: price,
                            qty: 1,
                            stock: stock
                        });
                    }
                },
                increaseQty(index) {
                    if (this.cart[index].qty < this.cart[index].stock) {
                        this.cart[index].qty++;
                    } else {
                        alert('Stok produk tidak mencukupi!');
                    }
                },
                decreaseQty(index) {
                    if (this.cart[index].qty > 1) {
                        this.cart[index].qty--;
                    } else {
                        this.removeFromCart(index);
                    }
                },
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },
                get totalPrice() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },
                submitCheckout() {
                    if (this.cart.length === 0) return;
                    if (this.cash < this.totalPrice) {
                        alert('Uang tunai kurang!');
                        return;
                    }

                    let formattedItems = this.cart.map(item => ({
                        product_id: item.product_id,
                        price: item.price,
                        qty: item.qty,
                        subtotal: item.price * item.qty
                    }));

                    fetch('{{ route("pos.checkout") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            items: formattedItems,
                            cash: this.cash,
                            total_price: this.totalPrice
                        })
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || 'Terjadi kesalahan');
                        return data;
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/pos/receipt/' + data.transaction_id;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal: ' + error.message);
                    });
                }
            }))
        });
    </script>
</x-app-layout>