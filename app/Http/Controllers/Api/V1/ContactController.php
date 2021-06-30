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

    // Permite establecer la imagen de un contacto, basandonos en el identificador del contacto
    public function set_image(Request $request)
    {
        // Primero validamos los elementos del requerimiento
        $request->validate([
            'id'    => 'required|exists:contacts',
            'image' => 'required'
        ]);

        // Ahora obtenemos el archivo con la imagen y lo guardamos en la carpeta correspondiente
        $contact_id = $request->id;
        $result = $request->file('image')->store('images/contacts');

        // Ahora buscamos el evento que tiene el identificador dado
        $contact = Contact::find($contact_id);
        $contact->image = $result;
        $contact->save();

        // Enviamos la respuesta de regreso al cliente
        return response()->json([
            'status' => 1,
            'message' => 'Image uploded successfully',
            'contact_id' => $contact_id
        ]);
    }

    // Permite obtener la imagen asociada al contacto
    public function get_image($contact_id)
    {
        // Ahora traemos la ruta donde se encuentra la imagen del contacto
        if (is_numeric($contact_id)) {
            $contact = Contact::findOrFail($contact_id);
            $imagen = $contact->image;

            // Si la imagen no existe, abortamos
            if (!Storage::exists($imagen)) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Contact with identifier $contact_id does not have an image",
                    'contact_id' => $contact_id
                ], 422);
            }

            // Enviamos la imagen al cliente
            $type = Storage::mimeType($imagen);
            return Storage::response($imagen, "imagen", ['Content-Type' => $type]);
        }

        // Hay un error en el tipo o formato del identificador del contacto
        return response()->json([
            "status" => "error",
            "message" => "Identifier $contact_id does not have right format",
            "contact_id" => $contact_id,
        ], 422);
    }

    // Obtener la información del contacto por identificador
    public function show($contact_id)
    {
        if (is_numeric($contact_id)) {
            $contact = Contact::find($contact_id);
            if ($contact)
            {
                return response()->json([
                    "status" => 1,
                    "data" => new ContactResource($contact)
                ]);
            }
            return response()->json([
                "status" => "error",
                "message" => "Identifier $contact_id does not exist in the system",
                "contact_id" => $contact_id
            ], 422);
        }

        // Hay un error en el tipo o formato del identificador del contacto
        return response()->json([
            "status" => "error",
            "message" => "Identifier $contact_id does not have right format",
            "contact_id" => $contact_id,
        ], 422);
    }

    // Actualizar la información del contacto
    public function update(Request $request, $id_evento)
    {
        // Primero validamos los elementos del requerimiento
        $request->validate([
            'last_name' => 'required',
            'first_name' => 'required',
            'email' => 'required|email|unique:contacts',
            'telephone' => 'required|integer'
        ]);

        // Ahora obtenemos el evento con el identificador dado
        $evento = Evento::find($id_evento);
        if (!$evento) {
            return response()->json([
                'status' => 'error',
                'message' => "Event with identifier $id_evento does not exist",
                'event_id' => $id_evento
            ]);
        }

        $contacto = $evento->contacts()->first();
        $isnew = false;

        if (!$contacto) {
            $contacto = new Contact();
            $isnew = true;
        }

        $contacto->lastName = trim(strtoupper($request->input('last_name')));
        $contacto->firstName = trim(strtoupper($request->input('first_name')));
        $contacto->email = $request->input('email');
        $contacto->telephone = $request->input('telephone', '');
        $contacto->image = '/images/contacts/default.png';
        $contacto->save();

        if ($isnew) {
            $contacto->eventos()->attach($evento);
        }

        return response()->json([
            'status' => 1,
            'message' => "Contact $contacto->id updated",
            'event_id' => $id_evento,
            'data' => new ContactResource($contacto)
        ]);

    }
}
