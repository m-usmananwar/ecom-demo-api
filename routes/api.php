<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Users\UserController;
use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Ads\AdController;
use App\Http\Controllers\Api\V1\Vendors\VendorController;

Route::middleware(['guest'])->group(function () {
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/ads', [AdController::class, 'index']);
    Route::get('/ads/{id}', [AdController::class, 'show']);
    Route::get('search', [AdController::class, 'search']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [UserController::class, 'logout']);
});
Route::middleware(['auth:sanctum', 'vendor'])->prefix('vendor')->group(function () {
    Route::apiResource('ads', VendorController::class);
});
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'indexUser']);
    Route::get('/users/{id}', [AdminController::class, 'showUser']);
    Route::put('/users/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser']);


    Route::get('/ads', [AdminController::class, 'indexAd']);
    Route::get('/ads', [AdminController::class, 'indexAd']);
    Route::get('/ads/{id}', [AdminController::class, 'showAd']);
    Route::put('/ads/{id}', [AdminController::class, 'updateAd']);
    Route::delete('/ads/{id}', [AdminController::class, 'destroyAd']);
});
