<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// --- IMPORT ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\RentalController as AdminRentalController; // Biarkan ini untuk Admin
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// --- IMPORT FRONTEND CONTROLLERS (lowercase) ---
use App\Http\Controllers\frontend\LandingController;
use App\Http\Controllers\frontend\ProductController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\OrderController; // <-- CONTROLLER BARU KITA!


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ======================================
// ============ AREA FRONTEND ===========
// ======================================

// Grup Halaman Publik
Route::name('frontend.')->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
    Route::get('/produk/{barang:id_barang}', [ProductController::class, 'show'])->name('produk.detail');
});

// Grup Keranjang (Cart)
Route::name('cart.')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('index');
    Route::post('/cart/add/{id_barang}', [CartController::class, 'addToCart'])->name('add');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/rental/now/{id_barang}', [CartController::class, 'rentNow'])->name('rental.now');
});

// Grup Pesanan (Order) - HANYA UNTUK YANG SUDAH LOGIN
Route::middleware(['auth'])->group(function () {

    // Rute untuk MEMBUAT pesanan (checkout)
    Route::post('/checkout/store', [OrderController::class, 'store'])->name('checkout.store');

    // Rute untuk MELIHAT & UPLOAD BUKTI (Pesanan Saya)
    Route::prefix('pesanan-saya')->name('frontend.order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/{id}/upload-bukti', [OrderController::class, 'uploadProof'])->name('uploadProof');
    });
});


// ======================================
// ============ AREA AUTH & PROFIL =====
// ======================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('frontend.order.index'); // Langsung ke "Pesanan Saya"
        }
    })->name('dashboard');

    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});


// ======================================
// ============ AREA ADMIN ==============
// ======================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('barang', BarangController::class);
        Route::get('orders', [AdminOrderController::class, 'index'])->name('order.index');
        // (Rute di bawah ini untuk langkah selanjutnya, tapi kita siapkan sekarang)
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('order.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('order.updateStatus');
    });

require __DIR__ . '/auth.php';
