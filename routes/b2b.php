<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Modul B2B (Mitra Industri & Korporat)
|--------------------------------------------------------------------------
| File ini dikhususkan untuk pengembangan fitur Mitra B2B (katalog korporat,
| reservasi Lab CNC / pengujian, quotation, invoice PPN/PPh 23/e-Meterai, dll.).
*/

Route::middleware(['auth'])
    ->prefix('b2b')
    ->name('b2b.')
    ->group(function () {
        // Tambahkan route modul Mitra Industri (B2B) di sini.
        // Contoh:
        // Route::get('/katalog', [B2bCatalogController::class, 'index'])->name('katalog');
        // Route::get('/reservasi-lab', [B2bBookingController::class, 'index'])->name('reservasi');
        // Route::get('/invoice', [B2bInvoiceController::class, 'index'])->name('invoice');
    });
