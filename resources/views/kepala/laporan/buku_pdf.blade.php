<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Buku</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        td {
            padding: 8px;
            vertical-align: top;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            float: right;
            width: 200px;
            text-align: center;
        }
        .space {
            height: 70px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Data Buku Perpustakaan</h2>
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Judul Buku</th>
                <th width="20%">Penulis</th>
                <th width="20%">Penerbit</th>
                <th width="10%">Tahun</th>
                <th width="10%">Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_buku as $buku)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $buku->judul }}</td>
                <td>{{ $buku->penulis }}</td>
                <td>{{ $buku->penerbit }}</td>
                <td class="text-center">{{ $buku->tahun_terbit }}</td>
                <td class="text-center">{{ $buku->stok }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Indramayu, {{ date('d F Y') }}</p>
        <p>Kepala Perpustakaan,</p>
        <div class="space"></div>
        <p><strong>( ____________________ )</strong></p>
    </div>

</body>
</html>