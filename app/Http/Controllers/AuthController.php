<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Recibir los datos que vienen del formulario
        $datos = $request->all();

        // 2. Encriptar la contraseña por seguridad antes de guardarla
        // (¡Nunca hay que guardarla en texto plano!)
        $datos['password'] = Hash::make($request->password);

        // 3. Crear el usuario en la base de datos usando nuestro Modelo
        $usuario = User::create($datos);

        // 4. Responderle al cliente que todo salió perfecto
        return response()->json([
            'mensaje' => '¡Usuario registrado con éxito!',
            'usuario' => $usuario
        ], 21); // El código 21 significa "Creado con éxito"
    }
}
