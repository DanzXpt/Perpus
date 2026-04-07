<?php

use App\Http\Controllers\AkunController;
use App\Http\Middleware\is_petugas_and_kepala;
use App\Http\Middleware\isAnggota;
use App\Http\Middleware\isKepala;
use App\Http\Middleware\isPetugas;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\KepalaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Perpustakaan Digital
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| 1. GUEST AREA
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Route Login yang sudah ada...
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Tambahkan Route Register ini
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'storeRegister']);

// Route Logout...
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 2. AUTH AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PENGEMBALIAN BUKU (PETUGAS)
    |--------------------------------------------------------------------------
    */
    Route::put('/transaksi/{id}/kembali', [TransaksiController::class, 'kembali'])
        ->name('petugas.kembali');


    /*
    |--------------------------------------------------------------------------
    | ROLE KEPALA PERPUSTAKAAN
    |--------------------------------------------------------------------------
    */
    Route::middleware(isKepala::class)->group(function () {
        Route::get('/kepala/dashboard', [KepalaController::class, 'index'])
            ->name('kepala.dashboard');

        // AKUN PENGGUNA
        Route::get('/kepala/pengguna', [KepalaController::class, 'users'])
            ->name('kepala.akun.index');

        Route::get('/kepala/pengguna/create', [AkunController::class, 'create'])
            ->name('kepala.akun.create');

        Route::post('/kepala/pengguna/store', [AkunController::class, 'store'])
            ->name('kepala.akun.store');

        Route::get('/kepala/pengguna/view/{id}', [AkunController::class, 'show'])
            ->name('kepala.akun.view');

        Route::get('/kepala/pengguna/edit/{id}', [AkunController::class, 'edit'])
            ->name('kepala.akun.edit');

        Route::put('/kepala/pengguna/update/{id}', [AkunController::class, 'update'])
            ->name('kepala.akun.update');

        Route::delete('/kepala/pengguna/delete/{id}', [AkunController::class, 'destroy'])
            ->name('kepala.akun.destroy');

        // TRANSAKSI
        Route::get('/kepala/transaksi', [KepalaController::class, 'transaksi'])
            ->name('kepala.transaksi.index');

        // LAPORAN
        Route::get('/kepala/laporan', [LaporanController::class, 'index'])
            ->name('kepala.laporan.index');

        Route::get('/kepala/laporan/cetak-buku', [LaporanController::class, 'cetakBuku'])
            ->name('kepala.laporan.buku_pdf');

        Route::get('/kepala/laporan/cetak-akun', [LaporanController::class, 'cetakAkun'])
            ->name('kepala.laporan.akun_pdf');
    });


    /*
|--------------------------------------------------------------------------
| CRUD BUKU
|--------------------------------------------------------------------------
*/
    Route::resource('buku', BukuController::class)->names([
        'index' => 'petugas.buku.index',
        'create' => 'petugas.buku.create',
        'store' => 'petugas.buku.store',
        'edit' => 'petugas.buku.edit',
        'update' => 'petugas.buku.update',
        'destroy' => 'petugas.buku.destroy',
    ])->parameters(['buku' => 'id'])->middleware(is_petugas_and_kepala::class);


    /*
    |--------------------------------------------------------------------------
    | ROLE PETUGAS
    |--------------------------------------------------------------------------
    */
    Route::middleware(isPetugas::class)->group(function () {

        Route::get('/petugas/dashboard', [PetugasController::class, 'index'])
            ->name('petugas.dashboard');

        Route::get('/petugas/transaksi', [PetugasController::class, 'transaksi'])
            ->name('petugas.transaksi');

        Route::put('/transaksi/{id}/kembali', [TransaksiController::class, 'kembalikanBuku'])
            ->name('petugas.kembali');

        /*
        |--------------------------------------------------------------------------
        | CRUD KATEGORI
        |--------------------------------------------------------------------------
        */
        Route::resource('kategori', KategoriController::class)->names([
            'index' => 'petugas.kategori.index',
            'create' => 'petugas.kategori.create',
            'store' => 'petugas.kategori.store',
            'edit' => 'petugas.kategori.edit',
            'update' => 'petugas.kategori.update',
            'destroy' => 'petugas.kategori.destroy',
        ])->parameters(['kategori' => 'id']);

        /*
        |--------------------------------------------------------------------------
        | PENGAJUAN PEMINJAMAN
        |--------------------------------------------------------------------------
        */
        Route::get('/petugas/pengajuan', [PetugasController::class, 'daftarPengajuan'])
            ->name('petugas.pengajuan.index');

        Route::post('/petugas/pengajuan/{id}/setujui', [PetugasController::class, 'setujuiPengajuan'])
            ->name('petugas.pengajuan.setujui');

        Route::post('/petugas/pengajuan/{id}/tolak', [PetugasController::class, 'tolakPengajuan'])
            ->name('petugas.pengajuan.tolak');

        Route::get('/petugas/transaksi/cetak', [TransaksiController::class, 'cetak'])
            ->name('petugas.transaksi.cetak');
    });


    /*
    |--------------------------------------------------------------------------
    | ROLE ANGGOTA
    |--------------------------------------------------------------------------
    */
    Route::middleware(isAnggota::class)->group(function () {
        Route::get('/anggota/dashboard', [AnggotaController::class, 'index'])
            ->name('anggota.dashboard');

        Route::get('/anggota/buku', [BukuController::class, 'index'])
            ->name('anggota.buku');

        Route::post('/anggota/pinjam/{id}', [PeminjamanController::class, 'store'])
            ->name('anggota.pinjam.store');

        Route::get('/anggota/pengajuan', [AnggotaController::class, 'pengajuan'])
            ->name('anggota.pengajuan.index');

        Route::get('/anggota/riwayat', [AnggotaController::class, 'riwayat'])
            ->name('anggota.riwayat');


        Route::post('/anggota/kembalikan/{id}', [AnggotaController::class, 'kembalikan'])
            ->name('anggota.kembalikan');
    });

    /*
    |--------------------------------------------------------------------------
    | PROFILE GLOBAL
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');
});