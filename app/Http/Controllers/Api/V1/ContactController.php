<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
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

    // Vamos a utilizar este método para eliminar un contacto del evento
    public function destroy($contact_id)
    {
        if (is_numeric($contact_id)) {
            $contact = Contact::find(intval($contact_id));
            if ($contact) {
                $contact->eventos()->detach();
                $contact->delete();
                return response()->json([
                    'status' => 1,
                    'message' => "Contact $contact_id deleted",
                    'contact_id' => $contact_id,
                ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => "Contact with identifier $contact_id does not exist",
                ], 422);
            }
        }
        return response()->json([
            "status" => "error",
            "message" => "Identifier $contact_id does not have right format",
            "contact_id" => $contact_id,
        ], 422);
    }

    // Permite obtener el contacto, dado el evento
    public function get_contact_by_event_id($event_id)
    {
        // Primero, verificamos que el evento exista
        if (is_numeric($event_id)) {
            $event = Evento::find($event_id);
            if ($event) {
                // AHora que estamos seguros que existe el evento, buscamos el contacto
                $contact = $event->contacts()->first();
                if ($contact) {
                    return response()->json([
                        'status' => 1,
                        'message' => 'OK',
                        'event_id' => $event_id,
                        'data' => new ContactResource($contact)
                    ]);
                }
                else {
                    return response()->json([
                       'status' => "error",
                       'message' => "Event $event_id does not have a contact",
                       'event_id' => $event_id
                    ], 422);
                }
            }
            else {
                return response()->json([
                    'status' => "error",
                    'message' => "Event $event_id does not exist",
                    'event_id' => $event_id
                ], 422);
            }
        }
        return response()->json([
            'status' => "error",
            'message' => "Event $event_id does not have a right format",
            'event_id' => $event_id
        ], 422);
    }
}
