<?php

use App\Modules\Auth\Infrastructure\Controllers\AuthApiController;
use App\Modules\Product\Infrastructure\Controllers\ProductApiController;
use App\Modules\Order\Infrastructure\Controllers\OrderApiController;
use App\Modules\Customer\Infrastructure\Controllers\CustomerApiController;
use App\Modules\Report\Infrastructure\Controllers\ReportApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - NovaOrders
|--------------------------------------------------------------------------
*/

// Auth (público)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/login', [AuthApiController::class, 'login']);
});

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::get('/auth/me', [AuthApiController::class, 'me']);

    // Products
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{id}', [ProductApiController::class, 'show']);

    // Products - admin/vendedor only
    Route::middleware('role:admin,vendedor')->group(function () {
        Route::post('/products', [ProductApiController::class, 'store']);
        Route::put('/products/{id}', [ProductApiController::class, 'update']);
        Route::delete('/products/{id}', [ProductApiController::class, 'destroy']);
    });

    // Categories
    Route::get('/categories', [ProductApiController::class, 'categories']);
    Route::middleware('role:admin,vendedor')->group(function () {
        Route::post('/categories', [ProductApiController::class, 'storeCategory']);
    });

    // Orders
    Route::get('/orders', [OrderApiController::class, 'index']);
    Route::get('/orders/{id}', [OrderApiController::class, 'show']);
    Route::post('/orders', [OrderApiController::class, 'store']);
    Route::patch('/orders/{id}/status', [OrderApiController::class, 'updateStatus'])
        ->middleware('role:admin,vendedor');
    Route::delete('/orders/{id}', [OrderApiController::class, 'destroy'])
        ->middleware('role:admin');

    // Customers
    Route::get('/customers', [CustomerApiController::class, 'index']);
    Route::get('/customers/{id}', [CustomerApiController::class, 'show']);
    Route::post('/customers', [CustomerApiController::class, 'store']);
    Route::put('/customers/{id}', [CustomerApiController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerApiController::class, 'destroy'])
        ->middleware('role:admin');

    // Reports - admin only
    Route::middleware('role:admin')->prefix('reports')->group(function () {
        Route::get('/sales', [ReportApiController::class, 'sales']);
        Route::get('/dashboard', [ReportApiController::class, 'dashboard']);
    });
});
