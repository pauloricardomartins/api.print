<?php

use App\Events\OrderAdded;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/store', [StoreController::class, 'store'])->middleware('role:store');

    Route::post('/orders', [OrderController::class, 'store'])->middleware('role:customer');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->middleware('role:store');

    Route::post('/orders/{id}/confirm', [OrderController::class, 'confirm'])->middleware('role:store');
});
