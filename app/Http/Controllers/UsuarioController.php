<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    
     //Inicia sesión del usuario
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'contrasenia' => 'required'
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Si estás usando contraseñas sin hash
        if ($usuario->contrasenia !== $request->contrasenia) {
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        }

        return response()->json([
            'mensaje' => 'Inicio de sesión exitoso',
            'usuario' => [
                'id' => $usuario->id_usuario,
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'rol' => $usuario->roll_usuario
            ]
        ]);
    }

    
    //Registrar un nuevo usuario
    public function registrar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuario,email',
            'contrasenia' => 'required|string|min:4',
            'roll_usuario' => 'required|in:admin,veterinarian,receptionist'
        ]);

        // Si deseas usar contraseñas cifradas, cambia esta línea:
        // 'contrasenia' => Hash::make($request->contrasenia)
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'contrasenia' => $request->contrasenia,
            'roll_usuario' => $request->roll_usuario
        ]);

        return response()->json([
            'mensaje' => 'Usuario registrado correctamente',
            'usuario' => $usuario
        ], 201);
    }

     // Listar todos los usuarios
    
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

   
     //Mostrar un usuario específico
     
    public function show($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json($usuario);
    }

     // Eliminar un usuario
    public function eliminar($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $usuario->delete();
        return response()->json(['mensaje' => 'Usuario eliminado correctamente']);
    }
}
