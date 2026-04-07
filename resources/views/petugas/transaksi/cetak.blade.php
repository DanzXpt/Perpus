<!DOCTYPE html>
<html>

<head>
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: Arial;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>Laporan Transaksi Peminjaman Buku</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($transaksi as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $t->user->name ?? '-' }}</td>
                    <td>{{ $t->buku->judul ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->tanggal_kembali)->format('d M Y') }}</td>
                    <td>{{ $t->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>