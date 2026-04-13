<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi Peminjaman</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 10px; font-size: 11px; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; text-align: center; }
        .text-center { text-align: center; }
        .text-capitalize { text-transform: capitalize; }
        
        .footer { margin-top: 30px; text-align: right; font-size: 12px; }
        .date-info { margin-bottom: 50px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Transaksi Peminjaman Buku</h2>
        <p>Perpustakaan Digital - Ahdan Muzaki</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th width="15%">Tgl Pinjam</th>
                <th width="15%">Tgl Kembali</th>
                <th width="12%">Status</th>
                <th width="12%">Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $t->user->name ?? 'User Dihapus' }}</td>
                    <td>{{ $t->buku->judul ?? 'Buku Dihapus' }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}</td>
                    <td class="text-center">
                        {{ $t->tanggal_kembali ? \Carbon\Carbon::parse($t->tanggal_kembali)->format('d M Y') : '-' }}
                    </td>
                    <td class="text-center text-capitalize">{{ $t->status }}</td>
                    <td class="text-center">Rp {{ number_format($t->denda ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="date-info">Dicetak pada: {{ date('d F Y H:i') }}</div>
        <p>Petugas Perpustakaan,</p>
        <br><br><br>
        <p><strong>( ____________________ )</strong></p>
    </div>

</body>
</html>