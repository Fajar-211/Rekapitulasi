<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RouteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth', 'verified'])->prefix('transaksi')->group(function () {
    Route::get('/penjualan', [RouteController::class, 'penjualan'])->name('penjualan');
    Route::get('/pembelian', [RouteController::class, 'pembelian'])->name('pembelian');
    Route::get('/operasional', [RouteController::class, 'operasional'])->name('operasional');
});

Route::middleware(['auth', 'verified'])->prefix('rekapitulasi')->group(function () {
    Route::get('/rekapitulasi', [RouteController::class, 'rekapitulasi'])->name('rekapitulasi');
});

Route::middleware(['auth', 'verified'])->prefix('master')->group(function () {
    Route::get('/customer', [RouteController::class, 'customer'])->name('customer');
    Route::get('/vendor', [RouteController::class, 'vendor'])->name('vendor');
    Route::get('/driver', [RouteController::class, 'driver'])->name('driver');
    Route::get('/pembayaran', [RouteController::class, 'pembayaran'])->name('pembayaran');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
