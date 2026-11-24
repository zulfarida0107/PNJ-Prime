<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Modul Admin P3M (Pengajuan & Validasi Produk Riset)
|--------------------------------------------------------------------------
| File ini dikhususkan untuk pengembangan fitur P3M (pengajuan produk/jasa
| riset, revisi kurasi, monitoring status SK Pimpinan, dll.).
*/

Route::middleware(['auth'])
    ->prefix('p3m')
    ->name('p3m.')
    ->group(function () {
        // Tambahkan route modul Admin P3M di sini.
        // Contoh:
        // Route::get('/dashboard', [P3mDashboardController::class, 'index'])->name('dashboard');
        // Route::resource('/produk', P3mProductController::class);
    });
