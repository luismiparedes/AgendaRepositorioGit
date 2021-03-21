<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\Null_;

class UserController extends Controller
{
    public function createUser(Request $request)
    {

        $response = "";

        $data = $request->getContent();

        $data = json_decode($data);

        if ($data != "") {


            if ($data) {

                $user = new User();

                $user->email = $data->email;
                // $user->password = $data->password;
                $user->password = Hash::make($data->password);


                try {
                    $user->save();
                    $response = "Datos guardados";
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

    public function loginUser(Request $request)
    {

        $response = "";

        $data = $request->getContent();
        $data = json_decode($data);


        //Obtenemos email y password de la request
        $requestEmail = $data->email;
        $requestPassword = $data->password;

        //buscamos el password en la base de datos del usuario, por email
        $userPassword = User::where('email', $requestEmail)->value('password');

        //Buscamos el ID corrspondiente en la base de datos
        $loggedUserId = User::where('email', $requestEmail)->value('id');
        $loggedUser = User::find($loggedUserId);


        if ($data != "") {
            if ($data) {

                //comprobamos si el usuario y la contraseña estan en la base de datos.
                //(User::where('email', '=', $requestEmail)->exists() && Hash::check($requestPassword, $userPassword))
                if (User::where('email', '=', $requestEmail)->exists() && Hash::check($requestPassword, $userPassword)) {

                    $token = $loggedUser->createToken('general')->accessToken;

                    $loggedUser->api_token = $token;
                    $loggedUser->save();


                    return response()->json([

                        'respuesta' => 'Accediste como' . " " . $loggedUser->email,

                        'token' => $token,
                        'id' => $loggedUser->id

                    ]);
                } else {
                    return response()->json([

                        'mensaje' => 'Nombre o contraseña incorrecta'

                    ], 422);
                }
            }
        } else {
            $response = 'Error datos introducidos';
        }
    }

    public function sendEmail(Request $request)
    {
        $response = "";
        $data = $request->getContent();
        $data = json_decode($data);



        $requestEmail = $data->email;

        $user = User::where('email', $requestEmail)->first();

        Log::debug("usuario introducida: " . $requestEmail);

        $userPassword = User::where('email', $requestEmail)->value('password');

        if (isset($userPassword)) {
            $generatePassword = uniqid();
            $newPassword = Hash::make($generatePassword);

            $user->password = $newPassword;

            $user->save();
            //return "La contraseña se cambió correctamente a:  ". $generatePassword;
            return response()->json([

                'mensaje' => "La contraseña se cambió correctamente a:  " . $generatePassword

            ], 200);
        }
        return response()->json([

            'mensaje' => 'Correo inválido'

        ], 422);
    }



    public function seeListUsers(Request $request)
    {
        $response = "";

        $apiToken = $request->bearerToken();
        $loggedUser = User::where('api_token', $apiToken)->first();

        Log::debug("usuario introducida: " . $loggedUser);


        $allUsers = User::all();





        if ($allUsers) {
            foreach ($allUsers as $user) {
                $usersEmails[] = [
                    "email" =>  $user['email'],
                ];
            }
            $response = $usersEmails;
        }



        return response($response);
    }



    public function changePassword(Request $request)
    {
        $response = "";
        $data = $request->getContent();
        $data = json_decode($data);



        //Obtenemos email y password de la request
        $requestPassword = $data->password;

        $apiToken = $request->bearerToken();
        $loggedUser = User::where('api_token', $apiToken)->first();


        Log::debug("usuario introducida: " . $requestPassword);





        if (isset($requestPassword)) {




            $newPassword = Hash::make($requestPassword);

            $loggedUser->password = $newPassword;

            $loggedUser->save();
            return "La contraseña se cambió correctamente a:  " . $requestPassword;
        }
        return response($response);
    }

    public function deleteUser(Request $request)
    {
        $response = "";

        $apiToken = $request->bearerToken();
        $loggedUser = User::where('api_token', $apiToken)->first();

        Log::debug("usuario a eliminar: " . $loggedUser);

        if ($loggedUser != null) {
            $userDelete = User::find($loggedUser->id);
            $userDelete->delete();
            return response()->json([

                'mensaje' => 'Se eliminó correctamente'

            ]);
        } else {
            return response()->json([

                'mensaje' => 'Token inválido'

            ], 401);
        }
    }

    public function logoutUser(Request $request)
    {


        $response = "";
        $data = $request->getContent();
        $data = json_decode($data);


        $apiToken = $request->bearerToken();
        $loggedUser = User::where('api_token', $apiToken)->value('api_token');

        $loggedUser->destroy();

        

        Log::debug("usuario a eliminar: " . $loggedUser);

        $response="El usuario salió correctamente";

        return response($response);
    }

    
}
