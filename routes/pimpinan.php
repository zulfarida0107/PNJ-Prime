<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Modul Pimpinan RTPU & Direktur PNJ
|--------------------------------------------------------------------------
| File ini dikhususkan untuk pengembangan fitur eksekutif (ringkasan
| pendapatan BLU, pengesahan SK tarif produk/jasa, laporan serapan, dll.).
*/

Route::middleware(['auth'])
    ->prefix('eksekutif')
    ->name('pimpinan.')
    ->group(function () {
        // Tambahkan route modul Pimpinan RTPU & Direktur di sini.
        // Contoh:
        // Route::get('/approval-sk', [PimpinanApprovalController::class, 'index'])->name('approval.sk');
        // Route::get('/laporan-keuangan', [PimpinanReportController::class, 'index'])->name('laporan');
    });
