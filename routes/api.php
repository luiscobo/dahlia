<?php

use App\Http\Controllers\Api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('login', [
    LoginController::class,
    'login'
]);

Route::get('logout', [
    LoginController::class,
    'logout'
])->middleware('auth:sanctum');
