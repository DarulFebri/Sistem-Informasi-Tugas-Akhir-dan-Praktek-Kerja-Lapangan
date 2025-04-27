<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\MahasiswaController;

// Auth Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/mahasiswa', [AdminController::class, 'mahasiswa'])->name('admin.mahasiswa');
    Route::get('/dosen', [AdminController::class, 'dosen'])->name('admin.dosen');
    // Tambahkan route admin lainnya
});

// Dosen Routes
Route::middleware(['auth', 'dosen'])->prefix('dosen')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dosen.dashboard');
    Route::get('/bimbingan', [DosenController::class, 'bimbingan'])->name('dosen.bimbingan');
    // Tambahkan route dosen lainnya
});

// Mahasiswa Routes
Route::middleware(['auth', 'mahasiswa'])->prefix('mahasiswa')->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
    Route::get('/bimbingan', [MahasiswaController::class, 'bimbingan'])->name('mahasiswa.bimbingan');
    // Tambahkan route mahasiswa lainnya
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Route untuk manajemen dosen
    Route::resource('dosen', DosenController::class)->except(['show']);
    Route::get('dosen/{dosen}', [DosenController::class, 'show'])->name('dosen.show');
    
    // Route untuk manajemen mahasiswa
    // ...
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // ... route lainnya
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // ... route lainnya
    
    // Route untuk mahasiswa
    Route::get('/mahasiswa', [AdminController::class, 'mahasiswa'])->name('admin.mahasiswa.index');
    Route::get('/mahasiswa/create', [AdminController::class, 'createMahasiswa'])->name('admin.mahasiswa.create');
    Route::post('/mahasiswa', [AdminController::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // ... route admin lainnya
});

// Dosen Routes
Route::middleware(['auth', 'dosen'])->prefix('dosen')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dosen.dashboard');
    // ... route dosen lainnya
});

// Mahasiswa Routes
Route::middleware(['auth', 'mahasiswa'])->prefix('mahasiswa')->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');
    // ... route mahasiswa lainnya
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Route untuk manajemen dosen
    Route::prefix('dosen')->group(function () {
        Route::get('/', [DosenController::class, 'index'])->name('dosen.index');
        Route::get('/create', [DosenController::class, 'create'])->name('dosen.create');
        Route::post('/', [DosenController::class, 'store'])->name('dosen.store');
        Route::get('/{dosen}', [DosenController::class, 'show'])->name('dosen.show');
        Route::get('/{dosen}/edit', [DosenController::class, 'edit'])->name('dosen.edit');
        Route::put('/{dosen}', [DosenController::class, 'update'])->name('dosen.update');
        Route::delete('/{dosen}', [DosenController::class, 'destroy'])->name('dosen.destroy');
    });

    // Route untuk manajemen mahasiswa (sama seperti dosen)
    Route::prefix('mahasiswa')->group(function () {
        Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        // ... tambahkan route lain untuk mahasiswa
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... route lainnya
    
    // Route untuk manajemen mahasiswa
    Route::prefix('mahasiswa')->group(function () {
        Route::get('/', [MahasiswaController::class, 'index'])->name('mahasiswa.index');
        Route::get('/create', [MahasiswaController::class, 'create'])->name('mahasiswa.create');
        Route::post('/', [MahasiswaController::class, 'store'])->name('mahasiswa.store');
        // ... tambahkan route lain sesuai kebutuhan
    });
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // ... route admin lainnya
});

// Dosen Routes
Route::middleware(['auth', 'dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dashboard');
    // ... route dosen lainnya
});

// Mahasiswa Routes
Route::middleware(['auth', 'mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
    // ... route mahasiswa lainnya
});