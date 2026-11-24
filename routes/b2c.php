<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Modul B2C (Mahasiswa & Dosen PNJ)
|--------------------------------------------------------------------------
| File ini dikhususkan untuk pengembangan fitur B2C (katalog produk riset,
| keranjang/checkout, pesanan saya, pembayaran QRIS/VA, dll.) agar terhindar
| dari merge conflict dengan modul lain.
*/

Route::middleware(['auth'])
    ->prefix('b2c')
    ->name('b2c.')
    ->group(function () {
        // Tambahkan route modul Mahasiswa / Dosen (B2C) di sini.
        // Contoh:
        // Route::get('/katalog', [B2cCatalogController::class, 'index'])->name('katalog');
        // Route::get('/pesanan', [B2cOrderController::class, 'index'])->name('pesanan');
        // Route::get('/pembayaran', [B2cPaymentController::class, 'index'])->name('pembayaran');
    });
