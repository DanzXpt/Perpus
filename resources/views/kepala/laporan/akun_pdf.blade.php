<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Akun</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <h2>LAPORAN DATA PENGGUNA PERPUSTAKAAN</h2>
        <p>Tanggal: {{ date('d-m-Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>role</th>
                <th>Identitas (NIS/NIP)</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($user as $user)
                <tr>
                    <td class="">{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        @if($user->role == 'anggota')
                            {{-- Ambil NIS dari relasi anggota --}}
                            {{ $user->anggota->nis ?? '-' }}
                        @elseif($user->role == 'petugas')
                            {{-- Ambil NIP dari relasi petugas --}}
                            {{ $user->petugas->nip ?? '-' }}
                        @elseif($user->role == 'kepala')
                            {{-- Ambil NIP dari relasi kepala --}}
                            {{ $user->kepala->nip ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>