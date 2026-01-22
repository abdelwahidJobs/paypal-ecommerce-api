<?php

require base_path('routes/payment.php');


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
