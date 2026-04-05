<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Jika kamu punya controller login sendiri
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\KepalaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route Utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// Halaman Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Authentication (Bawaan Laravel Breeze/Jetstream atau Manual)
// Auth::routes();

// ============================================================
// GROUP ROUTE: UNTUK USER YANG SUDAH LOGIN (AUTH)
// ============================================================
Route::middleware(['auth'])->group(function () {

    // --- ROUTE KHUSUS KEPALA PERPUSTAKAAN ---
    Route::middleware(['auth', 'checkLevel:kepala'])->group(function () {
        Route::get('/kepala/dashboard', [KepalaController::class, 'index'])->name('kepala.dashboard');
        Route::get('/kepala/laporan', [KepalaController::class, 'laporan'])->name('kepala.laporan');

        // Route Profil Kepala
        Route::get('/kepala/profile', [ProfileController::class, 'index'])->name('kepala.profile');
    });

    // --- ROUTE KHUSUS PETUGAS ---
    Route::middleware(['auth', 'checkLevel:petugas'])->group(function () {
        Route::get('/petugas/dashboard', [PetugasController::class, 'index'])->name('petugas.dashboard');
        // Menampilkan daftar buku
        Route::get('/petugas/buku', [BukuController::class, 'index'])->name('petugas.buku.index');
        // 1. Jalur untuk buka halaman formulir tambah buku
        Route::get('/petugas/buku/create', [BukuController::class, 'create'])->name('petugas.buku.create');
        // 2. Jalur untuk proses simpan ke database (Method POST)
        Route::post('/petugas/buku', [BukuController::class, 'store'])->name('petugas.buku.store');

        // 1. Jalur untuk menampilkan form edit (GET)
        Route::get('/petugas/buku/{id}/edit', [BukuController::class, 'edit'])->name('petugas.buku.edit');

        // 2. Jalur untuk proses update data (PUT)
        Route::put('/petugas/buku/{id}', [BukuController::class, 'update'])->name('petugas.buku.update');

        // Jalur untuk menghapus data buku (DELETE)
        Route::delete('/petugas/buku/{id}', [BukuController::class, 'destroy'])->name('petugas.buku.destroy');

        Route::get('/petugas/transaksi', [TransaksiController::class, 'index'])->name('petugas.transaksi');

        Route::put('/petugas/transaksi/{id}/kembali', [TransaksiController::class, 'kembali'])->name('petugas.kembali');


        // Route Profil Petugas
        Route::get('/petugas/profile', [ProfileController::class, 'index'])->name('petugas.profile');
    });

    // --- ROUTE KHUSUS ANGGOTA ---
    Route::middleware(['auth', 'checkLevel:anggota'])->group(function () {
        Route::get('/anggota/dashboard', [AnggotaController::class, 'index'])->name('anggota.dashboard');
        Route::get('/anggota/buku', [BukuController::class, 'index'])->name('anggota.buku');
        Route::get('/anggota/riwayat', [TransaksiController::class, 'index'])->name('anggota.riwayat');

        // Route Profil Anggota
        Route::get('/anggota/profile', [ProfileController::class, 'index'])->name('anggota.profile');
    });

    // Route Logout 
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});