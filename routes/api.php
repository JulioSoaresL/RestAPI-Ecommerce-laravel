<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerAuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);


Route::middleware([\App\Http\Middleware\IdentifyTenant::class])->group(function () {

    // CLIENTES
    Route::post('/customer/login', [CustomerAuthController::class, 'login']);

    Route::middleware('auth:customer')->group(function () {
        Route::prefix('customer')->group(function () {
            Route::get('me', [CustomerAuthController::class, 'me']);
        });

        Route::prefix('order')->group(function () {
            Route::get('/cart', [OrderController::class, 'index']);
            Route::get('/cart/{order}', [OrderController::class, 'show']);
            Route::post('/create', [OrderController::class, 'store']);
        });
    });

    // LOGISTAS E ADMS
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });

        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/create', [CategoryController::class, 'store']);
            Route::put('/update/{category}', [CategoryController::class, 'update']);
        });

        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::post('/create', [ProductController::class, 'store']);
        });
    });
});

