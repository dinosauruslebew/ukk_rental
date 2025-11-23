<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// ======================================
// ============ AUTH MANUAL =============
// ======================================

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Login & Register Manual
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout')->middleware('auth');

// Edit Profil Umum
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // === RENTAL DATA PROFILE (BARU) ===
    Route::get('/rental-profile', [\App\Http\Controllers\ProfileController::class, 'editRentalData'])->name('profile.rental.edit');
    Route::post('/rental-profile', [\App\Http\Controllers\ProfileController::class, 'updateRentalData'])->name('profile.rental.update');
});


// ======================================
// ============ FRONTEND AREA ===========
// ======================================

use App\Http\Controllers\Frontend\LandingController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
// PENTING: Gunakan Controller Frontend, bukan Admin
use App\Http\Controllers\Frontend\OrderController; 

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('frontend.landing');

// Produk
Route::prefix('produk')->name('frontend.produk.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{barang:id_barang}', [ProductController::class, 'showBarang'])
    ->name('detail');
});

// Paket
Route::prefix('paket')->name('frontend.paket.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index'); // Ini adalah rute untuk listing semua paket
    Route::get('/{paket:id_paket}', [ProductController::class, 'showPaket'])
    ->name('detail');   
});

// ==========================
// ========== CART ==========
// ==========================

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{id_barang}', [CartController::class, 'addToCart'])->name('add');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
});

// Tombol "Sewa Sekarang"
Route::post('/rental/now/{id_barang}', [CartController::class, 'rentNow'])
    ->name('cart.rental.now');

Route::post('/cart/add-paket/{id_paket}', [CartController::class, 'addPaket'])
    ->name('cart.addPaket');

// ============================================
// ============ CHECKOUT & PESANAN ============
// ============================================

Route::middleware('auth')->group(function () {

    // Menampilkan form checkout, menerima ID paket (opsional) dari landing page
    Route::get('/order/create/{paket_id?}', [OrderController::class, 'create'])->name('order.create'); 

    // Checkout ke DB
    Route::post('/checkout/store', [OrderController::class, 'store'])->name('checkout.store');

    // Pesanan Saya (User)
    Route::prefix('pesanan-saya')->name('frontend.order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/{id}/upload-bukti', [OrderController::class, 'uploadProof'])->name('uploadProof');
    });

    // Dashboard User / Admin Otomatis
    Route::get('/dashboard', function () {
        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('frontend.order.index');
    })->name('dashboard');
});


// ======================================
// ============ ADMIN AREA ==============
// ======================================

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController; // <-- Alias digunakan di sini
use App\Http\Controllers\Admin\PaketController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Barang
        Route::resource('barang', BarangController::class);

        // Orders
        Route::prefix('orders')->name('order.')->group(function () {
            // Gunakan alias AdminOrderController
            Route::get('/', [AdminOrderController::class, 'index'])->name('index');
            Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
            Route::patch('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
        });

        // Paket
        Route::resource('paket', PaketController::class);

        // admin/orders/{order} sudah ada show; tambahkan route patch untuk proses return
        // Ubah FQCN menjadi alias AdminOrderController::class
        Route::patch('/orders/{order}/return', [AdminOrderController::class, 'processReturn'])->name('order.processReturn');


    });