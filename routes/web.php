<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect halaman utama ke login atau dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ==========================================
// 1. ROUTE TAMU (Belum Login / Guest)
// ==========================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Lupa Password
    Route::get('/forgot-password', [AuthController::class, 'showForgetPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    // Reset Password
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// ==========================================
// 2. ROUTE UTAMA (Sudah Login / Auth)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard & Kasir Utama (POS)
    Route::get('/dashboard', function () {
        $products = \App\Models\Product::all();
        return view('pos.index', compact('products'));
    })->name('dashboard');

    // POS / Kasir Routes
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
    Route::get('/pos/receipt/{id}', [PosController::class, 'receipt'])->name('pos.receipt');
    Route::get('/pos/history', [PosController::class, 'history'])->name('pos.history');
    Route::get('/pos/report', [PosController::class, 'report'])->name('pos.report');

    // Manajemen Produk (Admin CRUD)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('products', ProductController::class);
    });

    // Profile Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});