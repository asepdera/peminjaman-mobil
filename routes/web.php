<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('/register', function () {
    return view('register');
});

Route::post('/login', [AuthController::class, 'login'])->name('loginAction');
Route::post('/register', [AuthController::class, 'register'])->name('registerAction');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logoutAction');
    Route::post('/management/peminjaman/filter', [PeminjamanController::class, 'filter'])->name('peminjaman.filter');
    Route::get('/detail/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('detailPeminjaman');

    Route::middleware('role:user')->group(function () {
        Route::get('/user', [UserController::class, 'index']);
        Route::get('/user/peminjaman', [UserController::class, 'peminjaman']);
        Route::post('/management/mobil/available', [MobilController::class, 'getAvailableCars']);
        Route::post('/create/peminjaman', [PeminjamanController::class, 'store'])->name('createPeminjaman');
        Route::post('/create/pengembalian', [PeminjamanController::class, 'kembalikan'])->name('createPengembalian');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/management', [ManagementController::class, 'dashboard']);
        Route::get('/management/mobil', [ManagementController::class, 'mobil']);
        Route::get('/management/peminjaman', [ManagementController::class, 'peminjaman']);
        Route::get('/management/mobil/add', [MobilController::class, 'index']);
        Route::get('/management/mobil/edit/{id}', [MobilController::class, 'edit']);
        Route::post('create/mobil', [MobilController::class, 'create'])->name('createMobil');
        Route::post('update/mobil/{id}', [MobilController::class, 'update'])->name('updateMobil');
        Route::delete('delete/mobil/{id}', [MobilController::class, 'destroy'])->name('deleteMobil');
        Route::post('/management/mobil/filter', [MobilController::class, 'filter'])->name('mobil.filter');
        Route::post('/management/peminjaman/status/{id}/{status}', [ManagementController::class, 'ubahStatus'])->name('ubahStatus');
    });
});