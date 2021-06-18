<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\ImageUploadController;
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

// Para descargar la imagen asociada a un evento
Route::get('v1/event/get_image', [ImageUploadController::class, 'download_event_image']);

// Todas las siguiente rutas necesitan pasar por una autenticación previa
Route::group(['middleware' => ['auth:sanctum']], function () {
    // Para conocer la información del usuario que está logeado
    Route::get('v1/user/profile', [EventController::class, 'profile']);

    // Para obtener la lista de todos los eventos en la base ed datos
    Route::get('v1/event/list', [EventController::class, 'list']);

    // Para registrar un nuevo evento
    Route::post('v1/event/register', [EventController::class, 'register']);

    // Para asignarle una imagen a un evento
    Route::post('v1/event/set_image', [ImageUploadController::class, 'upload_event_image']);

    // Para registrar un nuevo contacto en un evento
    Route::post('v1/contact/register', [ContactController::class, 'add_contact_to_event']);

});
