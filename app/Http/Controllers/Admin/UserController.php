<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the list of users
     */
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index')->with([
            'users' => $users
        ]);
    }




    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed', // debes enviar también password_confirmation
        'role' => 'nullable|string' // si usas roles
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => $validated['role'] ?? 'user', // ajusta según tus columnas
    ]);

    return response()->json([
        'message' => 'Usuario creado correctamente',
        'user' => $user
    ], 201);
}


    /**
     * Delete users
     */
    public function delete(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with([
            'success' => 'User has been deleted successfully'
        ]);
    }
}
