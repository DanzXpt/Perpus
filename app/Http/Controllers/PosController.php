<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    // Menampilkan Halaman POS & Daftar Produk
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil produk berdasarkan pencarian atau tampilkan semua
        $products = Product::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->get();

        // Ambil data keranjang dari session
        $cart = session()->get('cart', []);

        return view('pos.index', compact('products', 'cart'));
    }

    // Tambah Produk ke Keranjang (Session)
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty']++;
            $cart[$id]['subtotal'] = $cart[$id]['qty'] * $cart[$id]['price'];
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "qty" => 1,
                "subtotal" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    // Update atau Hapus Item di Keranjang
    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);

        if ($request->id && $request->qty) {
            if ($request->qty > 0) {
                $cart[$request->id]['qty'] = $request->qty;
                $cart[$request->id]['subtotal'] = $cart[$request->id]['qty'] * $cart[$request->id]['price'];
            } else {
                unset($cart[$request->id]);
            }
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back();
    }

    // Proses Simpan Transaksi Pembayaran
    public function checkout(Request $request)
    {
        $items = $request->input('items');

        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'Keranjang masih kosong!'], 400);
        }

        $total = collect($items)->sum(function ($item) {
            return $item['price'] * $item['qty'];
        });

        $cash = $request->input('cash');

        if ($cash < $total) {
            return response()->json(['success' => false, 'message' => 'Uang tunai kurang dari total belanja!'], 400);
        }

        $change = $cash - $total;

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'trx_code' => 'TRX-' . strtoupper(Str::random(8)),
                'user_id' => auth()->id() ?? 1,
                'total' => $total,
                'discount' => 0,
                'grand_total' => $total,
                'cash' => $cash,
                'change_amount' => $change,
            ]);

            foreach ($items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // Kurangi stok produk secara otomatis
                $product = Product::find($item['product_id']);
                if ($product) {
                    if ($product->stock < $item['qty']) {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi!");
                    }
                    $product->stock -= $item['qty'];
                    $product->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi Berhasil!',
                'transaction_id' => $transaction->id
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function receipt($id)
    {
        $transaction = Transaction::with(['details.product', 'user'])->findOrFail($id);
        return view('pos.receipt', compact('transaction'));
    }

    public function history()
    {
        // Ambil semua transaksi beserta relasi kasir (user), diurutkan dari terbaru
        $transactions = Transaction::with(['user', 'details.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Menggunakan pagination 10 data per halaman

        return view('pos.history', compact('transactions'));
    }

    public function report(Request $request)
    {
        // Jika tanggal filter kosong, set default dari awal bulan sampai hari ini (atau atur bebas)
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Ambil transaksi berdasarkan rentang tanggal
        $transactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $transactions->sum('total');
        $totalTransactions = $transactions->count();

        return view('pos.report', compact('transactions', 'totalRevenue', 'totalTransactions', 'startDate', 'endDate'));
    }
}