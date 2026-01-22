<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DeliveryOptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['jwt.cookie', 'jwt.auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']); // refresh token
});



Route::get('/products', [ProductController::class, 'index']);
Route::get('/delivery-options', [DeliveryOptionController::class, 'index']);



Route::get('/carts', [CartController::class, 'index']);
Route::get('/current-cart', [CartController::class, 'currentCart']);
Route::get('/carts/{cart}', [CartController::class, 'show']);
Route::get('/carts/{cart}/payment-summary', [CartController::class, 'paymentSummary']);
Route::post('/cart-items', [CartController::class, 'addItem']);
Route::put('/carts/{cart}/{product}', [CartController::class, 'updateDeliveryOption']);
Route::put('/carts/{cart}/{product}', [CartController::class, 'updateDeliveryOption']);
Route::delete('/carts/{cart}/{product}', [CartController::class, 'deleteItem']);

