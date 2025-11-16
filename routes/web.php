<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- IMPORT ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\Admin\PengaturanController;

// --- IMPORT FRONTEND CONTROLLERS ---
use App\Http\Controllers\Frontend\LandingController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;

use App\Models\Barang; // (Sebenarnya ini tidak perlu di file routes)

// ======================================
// ============ HALAMAN UTAMA ===========
// ======================================

// User
Route::get('/', [LandingController::class, 'index'])->name('frontend.landing');
Route::get('/produk', [ProductController::class, 'index'])->name('frontend.produk.index');
Route::get('/produk/{barang:id_barang}', [ProductController::class, 'show'])->name('frontend.produk.detail');

// --- ROUTES BARU UNTUK KERANJANG & SEWA ---
Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/rental/now/{id}', [CartController::class, 'rentNow'])->name('rental.now');


// ======================================
// ============ AUTH AREA ===============
// ======================================

Route::middleware(['auth'])->group(function () {

    // Profile user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard umum
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            // (Kode ini sekarang sudah benar karena route 'landing' sudah ada)
            return redirect()->route('landing');
        }
    })->name('dashboard');

    // Tombol logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        // (Kode ini sekarang sudah benar karena route 'landing' sudah ada)
        return redirect()->route('landing');
    })->name('logout');
});


// ======================================
// ============ ADMIN AREA ==============
// ======================================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('barang', BarangController::class);
        Route::get('/rental', [RentalController::class, 'index'])->name('rental.index');
        Route::post('/rental/{id}/confirm', [RentalController::class, 'confirm'])->name('rental.confirm');
        Route::post('/admin/rental/confirm/{id}', [RentalController::class, 'confirm'])->name('admin.rental.confirm'); // <-- Hati-hati, ini sepertinya duplikat
        Route::patch('/admin/rental/{id}/status', [RentalController::class, 'updateStatus'])->name('rental.updateStatus'); // <-- Disesuaikan

        // Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        // Route::post('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });


// ======================================
// ============ AUTH FILE ===============
// ======================================

require __DIR__ . '/auth.php';
