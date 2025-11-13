<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\RentalController;
use App\Http\Controllers\Admin\PengaturanController;

use App\Models\Barang;

// ======================================
// ============ HALAMAN UTAMA ===========
// ======================================

Route::get('/', function () {
    $barang = Barang::where('status', 'tersedia')->latest()->get();
    return view('welcome', compact('barang'));
})->name('landing');



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
            return redirect()->route('landing');
        }
    })->name('dashboard');

    // Tombol logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('landing');
    })->name('logout');
});


// =================================  =====
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
        Route::post('/admin/rental/confirm/{id}', [RentalController::class, 'confirm'])->name('admin.rental.confirm');
        Route::patch('/admin/rental/{id}/status', [App\Http\Controllers\Admin\RentalController::class, 'updateStatus'])->name('admin.rental.updateStatus');


        // Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        // Route::post('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });


// ======================================
// ============ AUTH FILE ===============
// ======================================

require __DIR__ . '/auth.php';
