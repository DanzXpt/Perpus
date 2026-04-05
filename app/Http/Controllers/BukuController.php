<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    // Menampilkan Tabel Kelola Buku
    public function index()
    {
        $buku = Buku::with('kategori')->latest()->get();
        return view('petugas.buku.index', compact('buku'));
    }

    // Menuju Halaman Tambah Buku
    public function create()
    {
        $kategori = Kategori::all();
        return view('petugas.buku.tambahbuku', compact('kategori'));
    }

    // Simpan Data Buku Baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'stok' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategoris,id', // Biar gak NULL lagi
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Buku::create($data);

        return redirect()->route('petugas.buku.index')->with('success', 'Buku berhasil ditambah!');
    }

    // Menuju Halaman Edit Buku
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = Kategori::all();
        return view('petugas.buku.editbuku', compact('buku', 'kategori'));
    }

    // Update Data Buku
    // JANGAN LUPA tambahkan ini di paling atas file (sebelum class)

    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        // 1. Validasi Ketat
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'stok' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        // 2. Ambil semua input kecuali cover dulu
        $data = $request->except('cover');

        // 3. Logika Upload Cover
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($buku->cover && Storage::disk('public')->exists($buku->cover)) {
                Storage::disk('public')->delete($buku->cover);
            }

            // Simpan cover baru
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        // 4. Update Database
        $buku->update($data);

        return redirect()->route('petugas.buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    // Hapus Buku
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return back()->with('success', 'Buku berhasil dihapus!');
    }

}