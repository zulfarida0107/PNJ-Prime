<?php

use App\Http\Controllers\MahasiswaB2CController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Modul B2C (Mahasiswa & Dosen PNJ)
|--------------------------------------------------------------------------
| File ini dikhususkan untuk pengembangan fitur B2C (katalog produk riset,
| reservasi fasilitas lab, formulir pemesanan, pesanan saya, pembayaran
| 100% Lunas VA/QRIS, pusat notifikasi, dan pengaturan akun SSO).
*/

Route::middleware(['auth'])
    ->prefix('b2c')
    ->name('b2c.')
    ->group(function () {
        // Dashboard & Katalog Lengkap
        Route::get('/dashboard', [MahasiswaB2CController::class, 'dashboard'])->name('dashboard');
        Route::get('/katalog', [MahasiswaB2CController::class, 'katalog'])->name('katalog');
        Route::get('/katalog/search', [MahasiswaB2CController::class, 'liveSearch'])->name('katalog.search');
        Route::get('/produk/{product}', [MahasiswaB2CController::class, 'showProduct'])->name('produk.show');

        // Formulir Pemesanan & Reservasi Lab (100% Lunas tanpa DP)
        Route::get('/pemesanan/{product}', [MahasiswaB2CController::class, 'createOrder'])->name('pemesanan.create');
        Route::post('/pemesanan/{product}', [MahasiswaB2CController::class, 'storeOrder'])->name('pemesanan.store');

        // Alur Ready Stock B2C (04b, 04b.1, 04b.2, 04b.3)
        Route::get('/ready-stock/{product}/checkout', [MahasiswaB2CController::class, 'checkoutReadyStock'])->name('ready-stock.checkout');
        Route::post('/ready-stock/{product}/checkout', [MahasiswaB2CController::class, 'storeReadyStock'])->name('ready-stock.store');
        Route::get('/ready-stock/{order}/pembayaran', [MahasiswaB2CController::class, 'showReadyStockPayment'])->name('ready-stock.pembayaran');
        Route::post('/ready-stock/{order}/konfirmasi', [MahasiswaB2CController::class, 'confirmReadyStockPayment'])->name('ready-stock.konfirmasi');

        // Alur Pre-Order B2C (04c, 04c.1, State A-E: 04c.2 - 04c.11)
        Route::get('/pre-order/{product}/checkout', [MahasiswaB2CController::class, 'checkoutPreOrder'])->name('preorder.checkout');
        Route::post('/pre-order/{product}/checkout', [MahasiswaB2CController::class, 'storePreOrder'])->name('preorder.store');
        Route::get('/pre-order/{order}', [MahasiswaB2CController::class, 'showPreOrder'])->name('preorder.show');
        Route::get('/pre-order/{order}/pembayaran', [MahasiswaB2CController::class, 'showPreOrderPayment'])->name('preorder.pembayaran');
        Route::post('/pre-order/{order}/konfirmasi', [MahasiswaB2CController::class, 'confirmPreOrderPayment'])->name('preorder.konfirmasi');
        Route::post('/pre-order/{order}/terima', [MahasiswaB2CController::class, 'confirmPreOrderReceived'])->name('preorder.terima');
        Route::post('/pre-order/{order}/ulasan', [MahasiswaB2CController::class, 'storePreOrderReview'])->name('preorder.ulasan');

        // Pesanan Saya & Detail Pesanan + QR Pass Akses Lab
        Route::get('/pesanan', [MahasiswaB2CController::class, 'orders'])->name('pesanan');
        Route::get('/pesanan/{order}', [MahasiswaB2CController::class, 'showOrder'])->name('pesanan.show');
        Route::post('/pesanan/{order}/batal', [MahasiswaB2CController::class, 'cancelOrder'])->name('pesanan.batal');

        // Pembayaran & Tagihan (VA Mandiri BLU / QRIS 100% Lunas) + Riwayat Pembayaran
        Route::get('/pembayaran', [MahasiswaB2CController::class, 'payments'])->name('pembayaran');
        Route::get('/pembayaran/riwayat', [MahasiswaB2CController::class, 'paymentHistory'])->name('pembayaran.riwayat');
        Route::post('/pembayaran/{invoice}/konfirmasi', [MahasiswaB2CController::class, 'confirmPayment'])->name('pembayaran.konfirmasi');

        // Pusat Notifikasi
        Route::get('/notifikasi', [MahasiswaB2CController::class, 'notifications'])->name('notifikasi');
        Route::post('/notifikasi/{id}/baca', [MahasiswaB2CController::class, 'markNotificationRead'])->name('notifikasi.read');
        Route::post('/notifikasi/baca-semua', [MahasiswaB2CController::class, 'markAllNotificationsRead'])->name('notifikasi.read-all');

        // Pengaturan Akun SSO
        Route::get('/pengaturan', [MahasiswaB2CController::class, 'settings'])->name('pengaturan');
        Route::put('/pengaturan', [MahasiswaB2CController::class, 'updateSettings'])->name('pengaturan.update');
    });
