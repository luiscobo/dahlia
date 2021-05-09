<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HTTP_Response;
use Illuminate\Support\Facades\Auth;

/*
 * Controlador para la tarea de autenticación
 */
class LoginController extends Controller
{
    // Invocado por la ruta, en la definicion del API
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (Auth::attempt($request->only('email', 'password')))
        {
            return response()->json([
                'token' => $request->user()->createToken($request->name)->plainTextToken,
                'message' => 'Success'
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized'
        ], HTTP_Response::HTTP_UNAUTHORIZED);
    }

    private function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'required'
        ]);
    }

    // Función para revocar el token y sacar el usuario del sistema
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request...
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Success'
        ]);
    }
}
