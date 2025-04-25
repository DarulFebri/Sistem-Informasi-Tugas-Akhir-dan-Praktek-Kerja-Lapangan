<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    // Langsung redirect ke dashboard tanpa validasi
    return redirect('/dashboard');
})->name('proses_login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/', function () {
    return view('welcome'); // Atau halaman utama Anda
});