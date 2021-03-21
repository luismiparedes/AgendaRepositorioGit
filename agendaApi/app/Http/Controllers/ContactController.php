<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function addContact(Request $request)
    {

        $response = "";
        $data = $request->getContent();
        $data = json_decode($data);

        $apiToken = $request->bearerToken();
        $loggedUser = User::where('api_token', $apiToken)->first();

        Log::debug("usuario introducida: " . $request->hasFile('photoContact'));

        if ($data != "") {


            if ($data) {

                $contact = new Contact();
                $contact->user_id = $loggedUser->id;
                $contact->name = $data->name;
                $contact->number = $data->number;


                try {
                    $contact->save();
                    $response = "Contacto Guardado";
                } catch (\Exception $e) {
                    $response = $e->getMessage();
                }
            }
        } else {
            return response()->json([

                'mensaje' => 'Error'

            ], 422);
        }

        return response($response);
    }



    public function listContact(Request $request)
    {
        $response = "";

        $apiToken = $request->bearerToken();
        $loggedUser = User::where('api_token', $apiToken)->first();

        Log::debug("usuario introducida: " . $loggedUser);





        $allContact = Contact::all();

        if ($allContact) {


            foreach ($allContact as $contact) {

                if ($contact->user_id == $loggedUser->id)

                    $contactList[] = [

                        "name" =>  $contact['name'],
                        "number" =>  $contact['number'],

                    ];
            }

            $response = $contactList;
        }
        return response($response);
    }

    public function deleteContact(Request $request)
    {


        $response = "";
        $data = $request->getContent();
        $data = json_decode($data);


        $apiToken = $request->bearerToken();
        $loggedUser = User::where('api_token', $apiToken)->first();



        $allContact = Contact::all();

        if ($allContact) {

            foreach ($allContact as $contact) {

                if (($contact->user_id == $loggedUser->id) && ($data->name == $contact->name) && ($data->number == $contact->number)) {

                    $contact->delete();

                    $response = "El contacto se eliminó correctamente";
                }
            }
        }
        return response($response);
    }

    public function modifyByName(Request $request)
    {


        $response = "";
        $data = $request->getContent();
        $data = json_decode($data);


        $apiToken = $request->bearerToken();
        $loggedUser = User::where('api_token', $apiToken)->first();



        $allContact = Contact::all();


        if ($allContact) {

            foreach ($allContact as $contact) {

                if (($contact->user_id == $loggedUser->id) && ($data->name == $contact->name) && ($data->number == $contact->number)) {

                    $contact->name = $data->modifyName;

                    $contact->save();

                    Log::debug("usuario introducida: " . $data->modifyName);

                    $response = "El contacto modificó el nombre a: " . $contact->name;
                }
            }
        }
        return response($response);
    }

    public function modifyByNumber(Request $request)
    {


        $response = "";
        $data = $request->getContent();
        $data = json_decode($data);


        $apiToken = $request->bearerToken();
        $loggedUser = User::where('api_token', $apiToken)->first();



        $allContact = Contact::all();


        if ($allContact) {

            foreach ($allContact as $contact) {

                if (($contact->user_id == $loggedUser->id) && ($data->name == $contact->name) && ($data->number == $contact->number)) {

                    $contact->number = $data->modifyNumber;

                    $contact->save();


                    $response = "El contacto modificó el numero a: " . $contact->number;
                }
            }
        }
        return response($response);
    }
}
