<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Modul Admin RTPU (Operasional, Kurasi, KYC, & Master Data P08)
|--------------------------------------------------------------------------
| File ini dikhususkan untuk pengembangan fitur Admin RTPU (master data unit,
| kategori/sub-kategori, kurasi produk P3M, verifikasi KYC mitra, manajemen
| pesanan & verifikasi pembayaran BLU).
*/

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Tambahkan route modul Admin RTPU di sini.
        // Contoh:
        // Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        // Route::resource('/units', AdminUnitController::class);
        // Route::get('/kurasi-produk', [AdminCurationController::class, 'index'])->name('kurasi');
        // Route::get('/verifikasi-kyc', [AdminKycController::class, 'index'])->name('kyc');
    });
