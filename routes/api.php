<?php

use App\Http\Controllers\api\CustomerController;
use App\Http\Controllers\api\DriverController;
use App\Http\Controllers\api\PurchaseController;
use App\Http\Controllers\api\SalerController;
use App\Http\Controllers\api\VendorController;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

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
});
Route::middleware(['auth:sanctum'])->prefix('transaksi')->group(function () {
    Route::get('/purchases', [PurchaseController::class, 'index']);
    Route::get('/purchase/{id}', [PurchaseController::class, 'show']);
    Route::post('/purchase', [PurchaseController::class, 'store']);
    Route::patch('/purchase/{id}', [PurchaseController::class, 'update']);
    Route::delete('/purchase/{id}', [PurchaseController::class, 'destroy']);

    Route::get('/salers', [SalerController::class, 'index']);
    Route::get('/saler/{id}', [SalerController::class, 'show']);
    Route::post('/saler', [SalerController::class, 'store']);
    Route::patch('/saler/{id}', [SalerController::class, 'update']);
    Route::delete('/saler/{id}', [SalerController::class, 'destroy']);
});
