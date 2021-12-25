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
        if ($request->isJson()){         
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
        $token = $user->createToken('authtoken');

        return response()->json([
            'message'=>'User Registered',
            'data'=> ['token' => $token->plainTextToken, 'user' => $user]
        ],201);
    }
    return response()->json(['error'=> 'Unauthorized'],401,[]);
    }   
    

    public function login(LoginRequest $request) {
        // Validaciones para hacer el login
        $request->authenticate();


        $token = $request->user()->createToken('authtoken');

       return response()->json(
           [
               'message'=>'Logged',
               'data'=> [
                   'user'=> $request->user(),
                   'token'=> $token->plainTextToken
               ]
           ]
        );   
    }

    public function userProfile($id) {
        
        if( User::where('id', $id)->exists() ){            
            $info = User::where('id', $id)->get();
            return response()->json([
                "status" => 1,
                "msg" => $info,
            ], 200);
        }else{            
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Empresa"
            ], 404);
        }
    }
    public function listaUsuarios() {        
        $users = User::all();
        return response()->json($users);
    }
    public function logout(Request $request) {

        $request->user()->tokens()->delete();

        return response()->json(
            [
                'message' => 'Logged out'
            ]
        );

    }
}