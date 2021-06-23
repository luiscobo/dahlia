<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventoCollection;
use App\Http\Resources\EventoResource;
use App\Models\Evento;
use Carbon\Carbon;
use DateTime;

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

        // Llenamos los diversos campos del evento
        $event->name = ucwords(strtolower($request->name));
        $event->description = $request->description;
        $event->location = $request->location ?? '';
        $event->user_id = $user_id;
        if ($request->filled('date_init')) {
            $event->dateInit = DateTime::createFromFormat('d/m/Y', $request->date_init);
        }
        else {
            $event->dateInit = Carbon::now();
        }
        if ($request->filled('date_end')) {
            $event->dateEnd = DateTime::createFromFormat('d/m/Y', $request->date_end);
        }
        else {
            $event->dateEnd = Carbon::now();
        }

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

    // Permite obtener la información del evento
    public function show($event_id)
    {
        // Obtenemos el objeto Evento asociado al $event_id que se pasa como parámetro
        $evento = Evento::find($event_id);

        if ($evento) {
            return response()->json([
                'status' => 1,
                'message' => "Event $event_id information OK",
                'data' => new EventoResource($evento)
            ]);
        }
        else {
            return response()->json([
                'status' => 'error',
                'message' => "Event $event_id does not exists in the database",
                'id' => $event_id
            ], 422);
        }
    }

    // Esta operación guarda la información de los datos que envía el usuario
    public function update(Request $request)
    {
        // Validamos los datos que acaba de enviar el usuario
        $request->validate(['event_id' => 'required|integer|exists:eventos,id']);

        // Ahora obtenemos los datos que acaba de enviar el usuario
        $event_id = $request->event_id;
        $evento = Evento::find($request->event_id);

        if ($request->filled('name')) {
            $evento->name = ucwords(strtolower($request->name));
        }
        if ($request->filled('description')) {
            $evento->name = $request->description;
        }
        if ($request->filled('location')) {
            $evento->name = ucwords(strtolower($request->location));
        }
        if ($request->filled('date_init')) {
            $evento->dateInit = DateTime::createFromFormat('d/m/Y', $request->date_init);
        }
        if ($request->filled('date_end')) {
            $evento->dateEnd = DateTime::createFromFormat('d/m/Y', $request->date_end);
        }

        // Guardamos el texto en la base de datos
        $evento->save();

        // Enviamos el mensaje de respuesta
        return response()->json([
            'status' => 1,
            'message' => "Event $event_id was updated successfully",
            'data' =>  new EventoResource($evento)
        ]);
    }
}
