<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KdsController;

// -------------------------------------------------------------
// Public / E-Menu Pelanggan (Tanpa Autentikasi Khusus)
// -------------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
})->name('emenu.index');

// -------------------------------------------------------------
// Protected Routes (Harus Login)
// -------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // Redireksi Dashboard berdasarkan Role setelah login
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return match ($user->role) {
            'ADMIN'    => redirect()->route('admin.dashboard'),
            'KASIR'    => redirect()->route('kasir.dashboard'),
            'KOKI'     => redirect()->route('kds.dapur'),
            'BARISTA'  => redirect()->route('kds.bar'),
            'PELAYAN'  => redirect()->route('pelayan.dashboard'),
            default    => redirect()->route('emenu.index'),
        };
    })->name('dashboard');

    // 1. Group Admin
    Route::middleware(['role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    });

    // 2. Group Kasir
    Route::middleware(['role:KASIR,ADMIN'])->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');
    });

    // 3. Group KDS Dapur (Nama route disesuaikan menjadi kds.dapur)
    Route::middleware(['role:KOKI,ADMIN'])->prefix('kds/dapur')->group(function () {
        Route::get('/', [KdsController::class, 'dapur'])->name('kds.dapur');
    });

    // 4. Group KDS Bar (Nama route disesuaikan menjadi kds.bar)
    Route::middleware(['role:BARISTA,ADMIN'])->prefix('kds/bar')->group(function () {
        Route::get('/', [KdsController::class, 'bar'])->name('kds.bar');
    });

    // 5. Group Pelayan
    Route::middleware(['role:PELAYAN,ADMIN'])->prefix('pelayan')->name('pelayan.')->group(function () {
        Route::get('/dashboard', function () {
            return view('pelayan.dashboard');
        })->name('dashboard');
    });

});

require __DIR__.'/auth.php';
