<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;


Route::prefix('api/payment')->group(function () {
    Route::post('/create-order', [PaymentController::class, 'createOrder']);
    Route::post('/orders/{id}/capture', [PaymentController::class, 'capture']);
});