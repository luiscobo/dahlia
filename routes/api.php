<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\ImageUploadController;
use App\Http\Controllers\Api\V1\SpeakerController;
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

// Para obtener la informacion de un evento
Route::get('v1/event/get_information/{id}', [EventController::class, 'show']);

// Permite obtener el contacto del evento en cuestion
Route::get('v1/event/id:{id}/get_contact', [ContactController::class, 'get_contact_by_event_id']);

// Para descargar la imagen asociada a un speaker
Route::get('v1/speaker/get_image', [ImageUploadController::class, 'download_speaker_image']);

// Obtener una lista con los speakers del evento
Route::get('v1/speaker/list', [SpeakerController::class, 'list']);


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

    // Para almacenar la información de un evento
    Route::post('v1/event/update', [EventController::class, 'update']);

    // Para registrar un nuevo contacto en un evento
    Route::post('v1/contact/register', [ContactController::class, 'add_contact_to_event']);

    // Para eliminar un contacto de un evento
    Route::delete('v1/contact/delete/{contact_id}', [ContactController::class, "destroy"]);

    // Para registrar un nuevo speaker o conferencista en el evento dado
    Route::post('v1/speaker/register', [SpeakerController::class, 'store']);

    // Para asignarle una imagen a un speaker
    Route::post('v1/speaker/set_image', [ImageUploadController::class, 'upload_speaker_image']);

    // Para eliminar un speaker del sistema
    Route::delete('v1/speaker/delete/{id}', [SpeakerController::class, "destroy"]);

});
