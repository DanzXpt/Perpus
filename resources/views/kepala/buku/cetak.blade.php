<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Buku - PerpusDigital</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
        th { bg-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4f46e5; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Klik untuk Cetak / Save PDF
        </button>
        <a href="{{ route('kepala.buku.index') }}" style="margin-left: 10px; color: #666;">Kembali</a>
    </div>

    <div class="header">
        <h2>LAPORAN DATA KOLEKSI BUKU</h2>
        <h3>Perpustakaan Digital Ahdan Muzaki</h3>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buku as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->penulis }}</td>
                <td>{{ $item->penerbit }}</td>
                <td>{{ $item->tahun_terbit }}</td>
                <td>{{ $item->stok }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Kepala Perpustakaan</strong></p>
    </div>

    <script>
        // Otomatis buka jendela print saat halaman dimuat
        // window.print(); 
    </script>
</body>
</html>