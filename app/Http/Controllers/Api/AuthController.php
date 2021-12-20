<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;


class AuthController extends Controller
{
  
   

    public function register(Request $request) {        
        // Validaciones para registrar un usuario
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',            
        ]);

        // Creamos al usuario
        $user = new User();
        $user->nombre = $request->nombre;
        $user->apellido = $request->apellido;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        event(new Registered($user));
        // La API nos devuelve una respuesta
        return response()->json([
            "status" => 1,
            "msg" => 'Alta de Usuario exitosa'
        ]);

    }

    public function login(LoginRequest $request) {
        // Validaciones para hacer el login
        $request->authenticate();


        $token = $request->user()->createToken('authtoken');

       return response()->json(
           [
               'message'=>'Logged ',
               'data'=> [
                   'user'=> $request->user(),
                   'token'=> $token->plainTextToken
               ]
           ]
        );   
    }

    public function userProfile() {        
        return response()->json([
            "status" => 1,
            "message" => "Acerca del perfil de usuario",
            "data" => auth()->user()
        ]);
    }
    public function listaUsuarios() {        
        $users = User::all();
        return response()->json($users);
    }
    public function logout(Request $request) {        
        $request->user()->tokens()->delete();
        return response()->json([
            "status" => 1,
            "message" => "Cierre de sesión OK"           
        ]);
    }
}