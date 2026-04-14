<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{

    public function index(Request $request)
    {
        $kategori = Kategori::all();
        $query = Buku::with('kategori');

        // FILTER KATEGORI
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // FILTER SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                    ->orWhere('penulis', 'like', "%$search%");
            });
        }

        $buku = $query->latest()->paginate(12);

        // CEK ROLE ANGGOTA
        if (auth()->user()->role == 'anggota') {

            $userId = auth()->id();

            // HITUNG PINJAMAN (PENTING)
            $jumlahPinjam = Peminjaman::where('user_id', $userId)
                ->whereIn('status', ['pending', 'dipinjam', 'terlambat'])
                ->count();

            $maxPinjam = 3;

            $bolehPinjam = $jumlahPinjam < $maxPinjam;

            return view('anggota.buku', compact(
                'buku',
                'kategori',
                'bolehPinjam' 
            ));
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
            'tahun_terbit' => 'required|integer|min:1900|max:2030',
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

        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'penerbit' => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit
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