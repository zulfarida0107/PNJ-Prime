<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Katalog Riset Publik (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/katalog', [PublicCatalogController::class, 'index'])->name('public.katalog');
Route::get('/katalog/{product}', [PublicCatalogController::class, 'show'])->name('public.katalog.detail');

/*
|--------------------------------------------------------------------------
| Modul Autentikasi (Login, Gateway SSO, Login Mitra, Register KYC, Logout)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/login/sso', [AuthController::class, 'showSso'])->name('login.sso');
Route::post('/login/sso', [AuthController::class, 'loginSso'])->name('login.sso.process');

Route::get('/login/mitra', [AuthController::class, 'showLoginMitra'])->name('login.mitra');

Route::get('/register/mitra', [AuthController::class, 'showRegisterMitra'])->name('register.mitra');
Route::post('/register/mitra', [AuthController::class, 'registerMitra'])->name('register.mitra.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard-mahasiswa', function () {
    return view('Mahasiswa atau Dosen (B2C).dashboard');
});

Route::get('/dashboard-mitra', function () {
    if (view()->exists('mitra.dashboard')) {
        return view('mitra.dashboard');
    }

    return view('Mahasiswa atau Dosen (B2C).layouts.mitra');
});

Route::get('/dashboard-pimpinan', function () {
    return view('Pimpinan atau Wadir.Pimpinan_Ringkasan_Eksekutif');
});

Route::get('/pimpinan', function () {
    return view('Pimpinan atau Wadir.Pimpinan_Ringkasan_Eksekutif');
});

Route::get('/pimpinan/ringkasan-eksekutif', function () {
    return view('Pimpinan atau Wadir.Pimpinan_Ringkasan_Eksekutif');
});

/*
|--------------------------------------------------------------------------
| Modular Route Files (Anti Merge Conflict)
|--------------------------------------------------------------------------
*/
require __DIR__.'/b2c.php';
require __DIR__.'/b2b.php';
require __DIR__.'/p3m.php';
require __DIR__.'/pimpinan.php';
require __DIR__.'/admin.php';
