<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index()
    {
        // Ini akan mencari semua orang yang masih punya hutang denda
        $denda = Peminjaman::with(['user', 'buku'])
            ->where('sisa_denda', '>', 0)
            ->get();

        return view('petugas.denda.index', compact('denda'));
    }
    public function bayar(Request $request, $id)
    {
        // 1. Ambil data model sebagai OBJEK 
        $data = Peminjaman::findOrFail($id);

        $bayar = $request->bayar;

        // 2. Validasi nominal
        if ($bayar <= 0) {
            return back()->with('error', 'Nominal tidak valid');
        }

        if ($bayar > $data->sisa_denda) {
            return back()->with('error', 'Bayar melebihi sisa denda');
        }

        // 3. Hitung Sekarang $data->dibayar
        $totalSudahDibayar = $data->dibayar + $bayar;
        $sisaBaru = $data->sisa_denda - $bayar;

        // 4. Update ke database
        $data->update([
            'dibayar' => $totalSudahDibayar,
            'sisa_denda' => $sisaBaru,
            'status_denda' => $sisaBaru <= 0 ? 'lunas' : 'nunggak'
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dicatat.');
    }
}