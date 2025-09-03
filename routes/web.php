<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RumahSakitController;
use App\Http\Controllers\PasienController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('Login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Protected routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard/{username}', [DashboardController::class, 'index'])->name('dashboard');

    // Rumah Sakit Routes - Resource style
    Route::prefix('rumah-sakit')->name('rumahsakit.')->group(function () {
        Route::get('/{user}', [RumahSakitController::class, 'index'])->name('index');
        Route::post('/', [RumahSakitController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [RumahSakitController::class, 'show'])->name('show');
        Route::put('/{id}', [RumahSakitController::class, 'update'])->name('update');
        Route::delete('/{id}', [RumahSakitController::class, 'destroy'])->name('destroy');
        Route::get('/search/data', [RumahSakitController::class, 'search'])->name('search');
    });

    // Pasien Routes - Resource style
    Route::prefix('pasien')->name('pasien.')->group(function () {
        Route::get('/{user}', [PasienController::class, 'index'])->name('index');
        Route::post('/', [PasienController::class, 'store'])->name('store');
        Route::get('/detail/{id}', [PasienController::class, 'show'])->name('show');
        Route::put('/{id}', [PasienController::class, 'update'])->name('update');
        Route::delete('/{id}', [PasienController::class, 'destroy'])->name('destroy');
        Route::get('/search/data', [PasienController::class, 'search'])->name('search');
        Route::get('/rumah-sakit/list', [PasienController::class, 'getRumahSakit'])->name('rumahsakit');
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
