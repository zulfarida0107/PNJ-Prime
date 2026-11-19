<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard-mahasiswa', function () {
    return view('mahasiswa.dashboard');
});

Route::get('/dashboard-mitra', function () {
    return view('mitra.dashboard');
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
