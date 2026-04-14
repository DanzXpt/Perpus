<?php

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
use App\Http\Controllers\AkunController;

// Middleware Aliases (Pastikan ini sudah terdaftar di bootstrap/app.php atau Kernel.php)
use App\Http\Middleware\isKepala;
use App\Http\Middleware\isPetugas;
use App\Http\Middleware\isAnggota;
use App\Http\Middleware\is_petugas_and_kepala;

/*
|--------------------------------------------------------------------------
| 1. GUEST AREA (Login & Register)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'storeRegister');
    Route::post('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| 2. AUTH AREA (Must be Logged In)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /* --- ROLE KEPALA PERPUSTAKAAN --- */
    Route::middleware(isKepala::class)->group(function () {
        Route::get('/kepala/dashboard', [KepalaController::class, 'dashboard'])->name('kepala.dashboard');
        
        // Manajemen Akun
        Route::prefix('kepala/pengguna')->name('kepala.akun.')->group(function () {
            Route::get('/', [KepalaController::class, 'users'])->name('index');
            Route::get('/create', [AkunController::class, 'create'])->name('create');
            Route::post('/store', [AkunController::class, 'store'])->name('store');
            Route::get('/view/{id}', [AkunController::class, 'show'])->name('view');
            Route::get('/edit/{id}', [AkunController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [AkunController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [AkunController::class, 'destroy'])->name('destroy');
        });

        Route::get('/kepala/transaksi', [KepalaController::class, 'transaksi'])->name('kepala.transaksi.index');
        
        // Laporan
        Route::prefix('kepala/laporan')->name('kepala.laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/cetak-buku', [LaporanController::class, 'cetakBuku'])->name('buku_pdf');
            Route::get('/cetak-akun', [LaporanController::class, 'cetakAkun'])->name('akun_pdf');
        });
    });

    /* --- CRUD BUKU (Akses Petugas & Kepala) --- */
    Route::resource('buku', BukuController::class)->names([
        'index'   => 'petugas.buku.index',
        'create'  => 'petugas.buku.create',
        'store'   => 'petugas.buku.store',
        'edit'    => 'petugas.buku.edit',
        'update'  => 'petugas.buku.update',
        'destroy' => 'petugas.buku.destroy',
    ])->parameters(['buku' => 'id'])->middleware(is_petugas_and_kepala::class);

    /* --- ROLE PETUGAS --- */
    Route::middleware(isPetugas::class)->group(function () {
        Route::get('/petugas/dashboard', [PetugasController::class, 'dashboard'])->name('petugas.dashboard');
        Route::get('/petugas/transaksi', [LaporanController::class, 'indexPetugas'])->name('petugas.transaksi.index');
        Route::get('/transaksi/cetak', [LaporanController::class, 'peminjamanBuku'])->name('petugas.transaksi.cetak');

        // CRUD Kategori
        Route::resource('kategori', KategoriController::class)->names([
            'index'   => 'petugas.kategori.index',
            'create'  => 'petugas.kategori.create',
            'store'   => 'petugas.kategori.store',
            'edit'    => 'petugas.kategori.edit',
            'update'  => 'petugas.kategori.update',
            'destroy' => 'petugas.kategori.destroy',
        ])->parameters(['kategori' => 'id']);

        // Peminjaman & Pengajuan
        Route::get('/petugas/pengajuan', [PetugasController::class, 'daftarPengajuan'])->name('petugas.pengajuan.index');
        Route::post('/petugas/transaksi/{id}/setuju', [TransaksiController::class, 'setuju'])->name('petugas.transaksi.setuju');
        Route::post('/petugas/transaksi/{id}/tolak', [TransaksiController::class, 'tolak'])->name('petugas.transaksi.tolak');
        Route::post('/petugas/konfirmasi/{id}', [PeminjamanController::class, 'konfirmasi'])->name('petugas.konfirmasi');

        /**
         * FIX AREA: MANAJEMEN DENDA
         * Arahkan index ke PetugasController@indexDenda agar sinkron dengan fitur Tab.
         */
        Route::get('/petugas/denda', [PetugasController::class, 'indexDenda'])->name('petugas.denda.index');
        Route::post('/petugas/denda/bayar-lunas/{id}', [PetugasController::class, 'bayarLunas'])->name('petugas.bayar-lunas');
    });

    /* --- ROLE ANGGOTA --- */
    Route::middleware(isAnggota::class)->group(function () {
        Route::get('/anggota/dashboard', [AnggotaController::class, 'index'])->name('anggota.dashboard');
        Route::get('/anggota/buku', [BukuController::class, 'index'])->name('anggota.buku');
        Route::get('/anggota/buku/{id}', [BukuController::class, 'show'])->name('anggota.buku.show');
        Route::post('/anggota/pinjam/{id}', [PeminjamanController::class, 'store'])->name('anggota.pinjam.store');
        Route::get('/anggota/pengajuan', [AnggotaController::class, 'pengajuan'])->name('anggota.pengajuan.index');
        Route::get('/anggota/riwayat', [AnggotaController::class, 'riwayat'])->name('anggota.riwayat');
        Route::post('/anggota/kembalikan/{id}', [AnggotaController::class, 'kembalikan'])->name('anggota.kembalikan');
        Route::get('/anggota/denda', [AnggotaController::class, 'denda'])->name('anggota.denda.index');
    });

    /* --- PROFILE GLOBAL --- */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::put('/profile', 'update')->name('profile.update');
        Route::put('/profile/password', 'updatePassword')->name('profile.password');
    });
});