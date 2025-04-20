<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // Iniciar sesión y generar token JWT
    public function login(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Obtener las credenciales del request
        $credentials = $request->only('email', 'password');

        try {
            // Intentar autenticar y generar el token
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales inválidas'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }

        // Responder con el token generado
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    // Cerrar sesión y revocar token
    public function logout(Request $request)
    {
        // Verificar si el token está presente
        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json(['error' => 'No token provided'], 400);
        }

        try {
            // Invalidar el token
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Sesión cerrada correctamente']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesión'], 500);
        }
    }

    // Obtener usuario autenticado
    public function me()
    {
        try {
            // Intentar obtener el usuario autenticado
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (JWTException $e) {
            // Manejo de diferentes tipos de excepciones
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error' => 'Token expirado'], 401);
            }

            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error' => 'Token inválido'], 401);
            }

            return response()->json(['error' => 'Token no proporcionado'], 401);
        }
    }
}
