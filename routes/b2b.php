<?php

use App\Http\Controllers\MitraB2BController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Modul B2B (Mitra Industri & Korporat)
|--------------------------------------------------------------------------
| File ini dikhususkan untuk pengembangan fitur Mitra B2B (dashboard,
| katalog produk & layanan riset B2B, detail produk/tax readiness, profil).
*/

Route::middleware(['auth'])
    ->prefix('b2b')
    ->name('b2b.')
    ->group(function () {
        Route::get('/dashboard', [MitraB2BController::class, 'dashboard'])->name('dashboard');
        Route::get('/katalog', [MitraB2BController::class, 'katalog'])->name('katalog');
        Route::get('/katalog/{product}', [MitraB2BController::class, 'detail'])->name('detail');
        Route::get('/profil', [MitraB2BController::class, 'profil'])->name('profil');
        Route::patch('/profil', [MitraB2BController::class, 'updateProfil'])->name('profil.update');
    });
