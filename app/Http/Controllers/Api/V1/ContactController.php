<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Evento;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Invocado cuando se desea agregar un nuevo contacto a un evento
    public function add_contact_to_event(Request $request)
    {
        // Primero validamos los elementos del requerimiento
        $request->validate([
            'event_id' => 'required|exists:eventos,id',
            'last_name' => 'required',
            'first_name' => 'required',
            'email' => 'required|email|unique:contacts'
        ]);

        // Ahora creamos el nuevo contacto, asociado al evento
        $contacto = new Contact();

        $contacto->lastName = trim(strtoupper($request->input('last_name')));
        $contacto->firstName = trim(strtoupper($request->input('first_name')));
        $contacto->email = $request->input('email');
        $contacto->telephone = $request->input('telephone', '');
        $contacto->image = '/images/contacts/default.png';
        $contacto->save();

        $id_evento = intval($request->input('event_id'));
        $evento = Evento::find($id_evento);
        $contacto->eventos()->attach($evento);

        // Enviamos la respuesta
        return response()->json([
            'status' => 1,
            'message' => 'Contact has been registered',
            'contact_id' => $contacto->id
        ]);
    }
}
