<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpeakerCollection;
use App\Models\Evento;
use App\Models\Speaker;
use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    /**
     * Display a listing of the speakers.
     */
    public function list(Request $request)
    {
        // Primero, obtenemos el identificador del evento
        if ($request->filled('event_id')) {
            $request->validate([
                'event_id' => 'required|exists:eventos,id'
            ]);

            // Vamos a obtener una lista con todos los conferencistas de la base de datos
            $event_id = $request->event_id;
            $evento = Evento::find($event_id);

            return new SpeakerCollection($evento->speakers()->get());
        }
        else {
            return new SpeakerCollection(Speaker::all());
        }
    }

    /**
     * Store a newly created speaker in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Primero, validamos los datos de entrada
        $request->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'type' => 'required|string',
            'email' => 'required|email|unique:speakers'
        ]);

        // Ahora, almacenamos los datos en la base de datos
        $speaker = new Speaker();
        $speaker->last_name = ucwords(strtolower(trim($request->last_name)));
        $speaker->first_name = ucwords(strtolower(trim($request->first_name)));
        $speaker->email = strtolower(trim($request->email));
        $speaker->type = strtoupper(trim($request->type));
        $speaker->telephone = $request->telephone ?? '';
        $speaker->image = '';
        $speaker->facebook = $request->facebook ?? '';
        $speaker->linkedin = $request->linkedin ?? '';
        $speaker->instagram = $request->instagram ?? '';
        $speaker->twitter = $request->twitter ?? '';
        $speaker->save();

        // Y ahora lo vinculamos al evento
        // $event_id = intval($request->input('event_id'));
        // $event = Evento::find($event_id);
        // $speaker->eventos()->attach($event);

        // Retornamos el estado de la respuesta
        return response()->json([
            'status' => 1,
            'message' => 'Speaker has been created',
            'speaker_id' => $speaker->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Speaker  $speaker
     * @return \Illuminate\Http\Response
     */
    public function show(Speaker $speaker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Speaker  $speaker
     * @return \Illuminate\Http\Response
     */
    public function edit(Speaker $speaker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Speaker  $speaker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Speaker $speaker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   $speaker_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($speaker_id)
    {
        if (is_numeric($speaker_id)) {
            $speaker = Speaker::find(intval($speaker_id));
            if ($speaker) {
                $speaker->eventos()->detach();
                $speaker->delete();
                return response()->json([
                    'status' => 1,
                    'message' => "Speaker $speaker_id deleted",
                    'speaker_id' => $speaker_id,
                ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => "Speaker with identifier $speaker_id does not exist",
                    'speaker_id' => $speaker_id
                ], 422);
            }
        }
        return response()->json([
            "status" => "error",
            "message" => "Identifier $speaker_id does not have right format",
            "speaker_id" => $speaker_id,
        ], 422);
    }

}
