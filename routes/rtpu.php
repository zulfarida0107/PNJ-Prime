<?php

use App\Http\Controllers\AdminRTPUController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Modul Admin RTPU — Batch 1 (Manajemen Operasional & Keuangan)
|--------------------------------------------------------------------------
| Prefix: /admin-rtpu | Name: admin.rtpu.*
| Terhubung otomatis dengan redirect akun demo admin.rtpu@pnj.ac.id (role: rtpu).
*/

Route::middleware(['auth'])
    ->prefix('admin-rtpu')
    ->name('admin.rtpu.')
    ->group(function () {
        // 1. Dashboard Eksekutif Admin RTPU
        Route::get('/', [AdminRTPUController::class, 'dashboard'])->name('index');
        Route::get('/dashboard', [AdminRTPUController::class, 'dashboard'])->name('dashboard');

        // 2. Daftar Antrean Pesanan Terpadu (B2C & B2B)
        Route::get('/antrean', [AdminRTPUController::class, 'antrean'])->name('antrean');
        Route::get('/antrean/ekspor-csv', [AdminRTPUController::class, 'exportCsv'])->name('export.csv');

        // 3. Detail & Validasi Pesanan B2C
        Route::get('/validasi-b2c', [AdminRTPUController::class, 'validasiB2cIndex'])->name('validasi-b2c.index');
        Route::get('/validasi-b2c/{order}', [AdminRTPUController::class, 'validasiB2c'])->name('validasi-b2c');
        Route::post('/validasi-b2c/{order}/aksi', [AdminRTPUController::class, 'actionB2c'])->name('validasi-b2c.action');

        // 4. Detail & Validasi Pesanan B2B + Penerbitan Invoice Resmi BLU (100% Lunas)
        Route::get('/validasi-b2b', [AdminRTPUController::class, 'validasiB2bIndex'])->name('validasi-b2b.index');
        Route::get('/validasi-b2b/{order}', [AdminRTPUController::class, 'validasiB2b'])->name('validasi-b2b');
        Route::post('/validasi-b2b/{order}/invoice', [AdminRTPUController::class, 'issueInvoiceB2b'])->name('validasi-b2b.invoice');
        Route::post('/validasi-b2b/{order}/aksi', [AdminRTPUController::class, 'actionB2b'])->name('validasi-b2b.action');

        // 5. Dokumen Cetak / PDF Resmi (Tab Baru, Auto-Print)
        Route::get('/cetak/laporan', [AdminRTPUController::class, 'printReport'])->name('print.laporan');
        Route::get('/cetak/invoice/{order}', [AdminRTPUController::class, 'printInvoice'])->name('print.invoice');
    });
