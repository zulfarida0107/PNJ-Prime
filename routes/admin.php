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
        // Tambahkan route modul Admin RTPU lainnya di sini.
    });

require __DIR__.'/rtpu.php';
