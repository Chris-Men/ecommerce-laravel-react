<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthUserController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    // Login de usuario
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        return response()->json([
            'token' => $token,
            'user'  => auth('api')->user()
        ]);
    }

    // Perfil del usuario autenticado
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    // Logout del usuario
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    // Obtener perfil del usuario autenticado
    public function showProfile()
    {
        $user = auth('api')->user();

        return response()->json([
            'user' => $user,
            'image_path' => $user->profile_image
                ? asset('storage/' . $user->profile_image)
                : 'https://cdn.pixabay.com/photo/2017/11/10/05/48/user-2935527_1280.png'
        ]);
    }

    // Actualizar perfil del usuario
   public function updateProfile(UpdateProfileRequest $request)
{
    $user = auth('api')->user();

    $data = $request->validated();

    // Si se sube una imagen
    if ($request->hasFile('profile_image')) {
        $imagePath = $request->file('profile_image')->store('profiles', 'public');
        $data['profile_image'] = 'storage/' . $imagePath;
    }

    $user->fill($data)->save(); // 👈 En lugar de update()

    return response()->json([
        'message' => 'Perfil actualizado correctamente.',
        'user' => $user,
        'image_path' => $user->image_path
    ]);
}


    // Eliminar imagen de perfil
    public function removeProfileImage()
    {
        $user = auth('api')->user();

        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->profile_image = null;
        $user->save();

        return response()->json([
            'message' => 'Imagen de perfil eliminada exitosamente.',
            'user' => $user
        ]);
    }
}
