<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    // Registro de usuario
    public function signin(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['message' => 'Usuario registrado con éxito'], 201);
    }

    // Inicio de sesión y generación del token
    public function login(Request $request)
    {
        
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // El usuario ha sido autenticado correctamente
            $user = Auth::user();
        } else {
            // Las credenciales no son válidas
            return response()->json(['error' => 'credenciales incorrectas']);
        }

        $payload = [
            'sub' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + (60 * 120) // Token expira en 2 horas
        ];

            $token = JWT::encode($payload, config('jwt.secret'),'HS256');
            // Devolvemos el token JWT al cliente

            return response()->json(['token' => $token]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully']);
    }

}
