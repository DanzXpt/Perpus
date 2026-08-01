<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            width: 300px;
            margin: 0 auto;
            padding: 10px;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        .border-dashed {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }

        .btn-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            /* Jarak antar tombol */
        }

        .btn {
            width: 100%;
            box-sizing: border-box;
            /* Agar padding tidak merusak lebar 100% */
            padding: 10px;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
        }

        .btn-print {
            background: #2563eb;
        }

        .btn-back {
            background: #4b5563;
        }

        @media print {
            .btn-container {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <!-- Header Toko -->
    <div class="text-center">
        <h3 style="margin: 0 0 5px 0;">TOKO POS ANDA</h3>
        <p style="font-size: 12px; margin: 0;">Jl. Contoh No. 123, Kota Anda</p>
        <p style="font-size: 12px; margin: 0;">Telp: 081234567890</p>
    </div>

    <div class="border-dashed"></div>

    <!-- Informasi Transaksi -->
    <div style="font-size: 12px; margin-bottom: 10px;">
        <p style="margin: 2px 0;">No. Transaksi : {{ $transaction->trx_code }}</p>
        <p style="margin: 2px 0;">Tanggal : {{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
        <p style="margin: 2px 0;">Kasir : {{ $transaction->user->name ?? 'Admin' }}</p>
    </div>

    <div class="border-dashed"></div>

    <!-- Daftar Belanja -->
    <div>
        @foreach($transaction->details as $detail)
            <div style="margin-bottom: 6px;">
                <div style="font-weight: bold;">{{ $detail->product->name ?? 'Produk' }}</div>
                <div class="flex">
                    <span>{{ $detail->qty }} x {{ number_format($detail->price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="border-dashed"></div>

    <!-- Rincian Pembayaran -->
    <div class="flex" style="font-size: 13px;">
        <span>TOTAL BELANJA:</span>
        <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
    </div>
    <div class="flex" style="font-size: 13px;">
        <span>BAYAR (CASH):</span>
        <span>Rp {{ number_format($transaction->cash, 0, ',', '.') }}</span>
    </div>
    <div class="flex" style="font-size: 13px; font-weight: bold; margin-top: 4px;">
        <span>KEMBALIAN:</span>
        <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
    </div>

    <div class="border-dashed"></div>

    <!-- Footer Struk -->
    <div class="text-center" style="font-size: 12px; margin-top: 15px;">
        <p style="margin: 2px 0;">Terima Kasih Atas Kunjungan Anda!</p>
        <p style="margin: 2px 0;">Jangan lupa datang lagi!</p>
    </div>

    <!-- Tombol Aksi (Otomatis Disembunyikan Saat Dicetak) -->
    <div class="btn-container">
        <button class="btn btn-print" onclick="window.print()">Cetak Ulang Struk</button>
        <a href="{{ route('pos.index') }}" class="btn btn-back">Kembali ke Halaman POS</a>
    </div>

</body>

</html>