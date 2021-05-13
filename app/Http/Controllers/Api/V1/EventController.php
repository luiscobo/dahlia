<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventoCollection;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Invocado cuando se necesita crear un evento
    public function register(Request $request)
    {
        // Validamos los datos de entrada
        $request->validate([
            'name' => 'required|unique:eventos',
            'description' => 'required'
        ]);

        // Obtenemos el identificador del usuario
        $user_id = auth()->user()->id;

        // Ahora creamos el nuevo registro de los eventos
        $event = new Evento();

        $event->name = ucwords($request->name);
        $event->description = $request->description;
        $event->location = $request->location ?? '';
        $event->user_id = $user_id;

        // Lo guardamos en la base de datos
        $event->save();

        // Enviamos la respuesta
        return response()->json([
            'status' => 1,
            'message' => 'Event has been registered',
            'event_id' => $event->id
        ]);
    }

    // Invocado cuando queremos conocer todos los eventos de la BD
    public function list()
    {
        // Obtenemos el usuario
        $user_id = auth()->user()->id;

        // Obtenemos todos los eventos del usuario
        $events = Evento::where('user_id', $user_id)->get();

        // Enviamos la respuesta
        return new EventoCollection(Evento::all());

    }

    // Permite obtener el perfil del usuario que está logeado
    public function profile()
    {
        return response()->json([
            'status' => 1,
            'message' => 'User Profile Information',
            'data' => auth()->user()
        ]);
    }
}
