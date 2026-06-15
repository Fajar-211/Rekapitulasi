<?php

use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\DriverController;
use App\Http\Controllers\api\MethodeController;
use App\Http\Controllers\api\OperasionalController;
use App\Http\Controllers\api\PurchaseController;
use App\Http\Controllers\api\RekapitulasiController;
use App\Http\Controllers\api\SalerController;
use App\Http\Controllers\api\TransaksiController;
use App\Http\Controllers\api\TypeOperasionalController;
use App\Http\Controllers\api\VendorController;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('master')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customer', [CustomerController::class, 'store']);
    Route::patch('/customer/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);

    Route::get('/drivers', [DriverController::class, 'index']);
    Route::post('/driver', [DriverController::class, 'store']);
    Route::patch('/driver/{id}', [DriverController::class, 'update']);
    Route::delete('/drivers/{id}', [DriverController::class, 'destroy']);

    Route::get('/vendors', [VendorController::class, 'index']);
    Route::post('/vendor', [VendorController::class, 'store']);
    Route::patch('/vendor/{id}', [VendorController::class, 'update']);
    Route::delete('/vendors/{id}', [VendorController::class, 'destroy']);

    Route::get('/types', [TypeOperasionalController::class, 'index']);
    Route::post('/type', [TypeOperasionalController::class, 'store']);
    Route::patch('/type/{id}', [TypeOperasionalController::class, 'update']);
    Route::delete('/types/{id}', [TypeOperasionalController::class, 'destroy']);

    Route::get('/methods', [MethodeController::class, 'index']);
    Route::post('/methode', [MethodeController::class, 'store']);
    Route::patch('/methode', [MethodeController::class, 'update']);
    Route::delete('/methode/{id}', [MethodeController::class, 'destroy']);
});
Route::middleware(['auth:sanctum'])->prefix('transaksi')->group(function () {
    Route::get('/purchases', [PurchaseController::class, 'index']);
    Route::get('/purchase/{id}', [PurchaseController::class, 'show']);

    Route::get('/salers', [SalerController::class, 'index']);
    Route::get('/saler/{id}', [SalerController::class, 'show']);

    Route::get('/operasionals', [OperasionalController::class, 'index']);
    Route::get('/operasional/{id}', [OperasionalController::class, 'show']);
    Route::post('/operasional', [OperasionalController::class, 'store']);
    Route::patch('/operasional/{id}', [OperasionalController::class, 'update']);
    Route::delete('/operasional/{id}', [OperasionalController::class, 'destroy']);

    Route::post('/transaksi', [TransaksiController::class, 'store']);
    Route::patch('/pembelian/{id}', [PurchaseController::class, 'update']);
    Route::patch('/penjualan/{id}', [SalerController::class, 'update']);
});

Route::middleware(['auth:sanctum'])->prefix('rekapitulasi')->group(function () {
    Route::get('/rekapitulasi', [RekapitulasiController::class, 'index']);
});
