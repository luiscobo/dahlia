<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Evento;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    // Para almacenar un elemento de agenda asociado a un evento
    public function store(Request $request, $id_evento)
    {
        // Primero validamos lo que nos envía el cliente
        $request->validate([
            'day' => 'required|date_format:Y-m-d',
            'hour_init' => 'required|date_format:H:i',
            'hour_end' => 'required|date_format:H:i',
            'title' => 'required|string'
        ]);

        // Ahora verificamos que el evento exista
        $evento = Evento::find($id_evento);
        if (!$evento) {
            return response()->json([
                'status' => 'error',
                'message' => "Event with identifier $id_evento does not exist",
                'event_id' => $id_evento
            ]);
        }

        // Ahora creamos el elemento de agenda en la base de datos
        $agenda = new Agenda();
        $agenda->evento_id = $id_evento;
        $agenda->date_agenda = $request->day;
        $agenda->time_begin = $request->hour_init;
        $agenda->time_end = $request->hour_end;
        $agenda->title = $request->title;
        $agenda->location = $request->location ?? '';
        $agenda->description = $request->description ?? '';
        $agenda->save();

        // Ahora obtenemos la respuesta al cliente
        return response()->json([
            'status' => 1,
            'message' => 'Agenda has been created successfully',
            'agenda_id' => $agenda->id,
            'event_id' => $id_evento
        ]);
    }
}
