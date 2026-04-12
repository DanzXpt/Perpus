<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{

    public function index(Request $request)
    {
        $kategori = Kategori::all();
        $query = Buku::with('kategori');

        // 1. Filter Kategori (Jalankan duluan)
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // 2. Filter Search (Bungkus dalam function biar gak ngerusak kategori)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('penulis', 'like', "%$search%");
            });
        }

        $buku = $query->latest()->paginate(12);

        if (auth()->user()->role == 'anggota') {
            return view('anggota.buku', compact('buku', 'kategori'));
        }
        return view('petugas.buku.index', compact('buku', 'kategori'));
    }



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
            'tahun_terbit' => 'required|date|date_format:Y-m-d',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategoris,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
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

        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:2030',
            'stok' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'kategori_id' => 'required|exists:kategoris,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // cover update
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
            $buku->cover = $coverPath;
        }

        // FIX INI
        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit . '-01-01'
        ]);

        return redirect()->route('petugas.buku.index')
            ->with('success', 'Buku berhasil diperbarui!');
    }

    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        return view('anggota.buku.show', compact('buku'));
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