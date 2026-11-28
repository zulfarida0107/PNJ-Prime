<?php

use App\Http\Controllers\AdminP3MController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Modul Admin P3M (Pengelola Produk Unit / Reseller)
|--------------------------------------------------------------------------
| Khusus pengembangan fitur Admin P3M: dashboard pengajuan, form pengajuan
| produk baru berbasis Master Data P08, pemantauan status RTPU, revisi
| pengajuan ulang, riwayat arsip, serta ekspor data.
*/

Route::middleware(['auth'])
    ->prefix('admin-p3m')
    ->name('admin.p3m.')
    ->group(function () {
        Route::get('/', [AdminP3MController::class, 'dashboard'])->name('index');
        Route::get('/dashboard', [AdminP3MController::class, 'dashboard'])->name('dashboard');

        Route::get('/pengajuan/baru', [AdminP3MController::class, 'create'])->name('create');
        Route::post('/pengajuan', [AdminP3MController::class, 'store'])->name('store');

        Route::get('/pemantauan', [AdminP3MController::class, 'pemantauan'])->name('pemantauan');

        Route::get('/revisi/{product}', [AdminP3MController::class, 'revisi'])->name('revisi');
        Route::put('/revisi/{product}', [AdminP3MController::class, 'updateRevisi'])->name('revisi.update');
        Route::delete('/pengajuan/{product}', [AdminP3MController::class, 'cancel'])->name('cancel');

        Route::get('/riwayat', [AdminP3MController::class, 'riwayat'])->name('riwayat');
        Route::get('/ekspor', [AdminP3MController::class, 'exportCsv'])->name('export');
        Route::get('/cetak-laporan', [AdminP3MController::class, 'printReport'])->name('print');
        Route::get('/sk/{product}', [AdminP3MController::class, 'printSk'])->name('print-sk');
    });

// Alias singkat /p3m -> redirect ke Dashboard Admin P3M
Route::middleware(['auth'])
    ->prefix('p3m')
    ->name('p3m.')
    ->group(function () {
        Route::get('/', fn () => redirect()->route('admin.p3m.dashboard'))->name('index');
        Route::get('/dashboard', fn () => redirect()->route('admin.p3m.dashboard'))->name('dashboard');
    });
