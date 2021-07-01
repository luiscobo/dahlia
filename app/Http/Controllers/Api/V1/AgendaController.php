<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Evento;
use App\Models\Speaker;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    // Para almacenar un elemento de agenda asociado a un evento
    public function store(Request $request, $id_evento)
    {
        // Primero validamos lo que nos envía el cliente
        $request->validate([
            'day' => 'required|date_format:d/m/Y',
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
        $agenda->location = $request->locate ?? '';
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

    // Permite agregar un conferencista a la agenda dada
    public function add_speaker($id_evento, $id_agenda, $id_speaker)
    {
        // Verificamos la existencia del evento
        $evento = Evento::find($id_evento);
        if (!$evento) {
            return response()->json([
                'status' => 'error',
                'message' => "Event with identifier $id_evento does not exist",
                'event_id' => $id_evento
            ]);
        }

        // Ahora buscamos la agenda en el evento correspondiente.
        $agenda = Agenda::find($id_agenda);
        if (!$agenda) {
            return response()->json([
                'status' => 'error',
                'message' => "Agenda with identifier $id_agenda does not exist",
                'agenda_id' => $id_agenda
            ]);
        }
        if ($agenda->evento_id != $id_evento) {
            return response()->json([
                'status' => 'error',
                'message' => "Event with identifier $id_evento does not have an agenda with code $id_agenda",
                'event_id' => $id_evento,
                'agenda_id' => $id_agenda
            ]);
        }

        // Verificamos que el speaker existe en la base de datos
        $speaker = Speaker::find($id_speaker);
        if (!$speaker) {
            return response()->json([
                'status' => 'error',
                'message' => "Speaker with identifier $id_speaker does not exist",
                'speaker_id' => $id_speaker
            ]);
        }

        // Ahora si guardamos la información en la base de datos
        $agenda->speakers()->attach($speaker);

        // Enviamos la respuesta
        return response()->json([
            'status' => 1,
            'message' => 'Speaker has been registered',
            'agenda_id' => $agenda->id,
            'speaker_id' => $id_speaker
        ]);

    }
}
