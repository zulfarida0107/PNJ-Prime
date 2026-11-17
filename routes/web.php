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
