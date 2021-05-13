<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\V1\EventController;
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

// Todas las siguiente rutas necesitan pasar por una autenticación previa
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Para conocer la información del usuario que está logeado
    Route::get('v1/user/profile', [EventController::class, 'profile']);

    // Para obtener la lista de todos los eventos en la base ed datos
    Route::get('v1/event/list', [EventController::class, 'list']);

    // Para registrar un nuevo evento
    Route::post('v1/event/register', [EventController::class, 'register']);

});
