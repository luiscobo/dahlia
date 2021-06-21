<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Evento;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ImageUploadController extends Controller
{
    // Permite guardar una imagen de un evento desde el cliente
    public function upload_event_image(Request $request)
    {
        // Primero validamos los elementos del requerimiento
        $request->validate([
            'id'    => 'required|exists:eventos,id',
            'image' => 'required'
        ]);

        // Ahora obtenemos el archivo con la imagen y lo guardamos en la carpeta correspondiente
        $event_id = $request->id;
        $result = $request->file('image')->store('images/events');

        // Ahora buscamos el evento que tiene el identificador dado
        $evento = Evento::find($event_id);
        $evento->imagen = $result;
        $evento->save();

        // Enviamos la respuesta de regreso al cliente
        return response()->json([
            'status' => 1,
            'message' => 'Image uploded successfully',
            'event_id' => $event_id
        ]);
    }

    // Permite obtener el archivo de imagen asociado a un evento
    public function download_event_image(Request $request)
    {
        // Primero validamos los elementos del requerimiento
        $request->validate([
            'id' => 'required|exists:eventos,id'
        ]);

        // Ahora traemos la ruta donde se encuentra la imagen del evento
        $evento_id = $request->id;
        $evento = Evento::findOrFail($evento_id);
        $imagen = $evento->imagen;

        // Si la imagen no existe, abortamos
        if (!Storage::exists($imagen)) {
            abort(404);
        }

        // Enviamos la imagen al cliente
        $type = Storage::mimeType($imagen);
        return Storage::response($imagen, "imagen", ['Content-Type' => $type]);
    }

    // Permite guardar una imagen de un evento desde el cliente
    public function upload_speaker_image(Request $request)
    {
        // Primero validamos los elementos del requerimiento
        $request->validate([
            'id'    => 'required|exists:speakers',
            'image' => 'required'
        ]);

        // Ahora obtenemos el archivo con la imagen y lo guardamos en la carpeta correspondiente
        $speaker_id = $request->id;
        $result = $request->file('image')->store('images/speakers');

        // Ahora buscamos el evento que tiene el identificador dado
        $speaker = Speaker::find($speaker_id);
        $speaker->image = $result;
        $speaker->save();

        // Enviamos la respuesta de regreso al cliente
        return response()->json([
            'status' => 1,
            'message' => 'Image uploded successfully',
            'event_id' => $speaker_id
        ]);
    }

    // Permite obtener el archivo de imagen asociado a un evento
    public function download_speaker_image(Request $request)
    {
        // Primero validamos los elementos del requerimiento
        $request->validate([
            'id' => 'required|exists:speakers,id'
        ]);

        // Ahora traemos la ruta donde se encuentra la imagen del evento
        $speaker_id = $request->id;
        $speaker = Speaker::findOrFail($speaker_id);
        $imagen = $speaker->image;

        // Si la imagen no existe, abortamos
        if (!Storage::exists($imagen)) {
            abort(404);
        }

        // Enviamos la imagen al cliente
        $type = Storage::mimeType($imagen);
        return Storage::response($imagen, "imagen", ['Content-Type' => $type]);
    }
}
