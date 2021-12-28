<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\LoginRequest;


class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        // Validaciones para hacer el login
        $request->authenticate();


        $token = $request->user()->createToken('authtoken');

       return response()->json(
           [
               'message'=>'Inicio de Seccion Exitoso',
               'data'=> [
                   'user'=> $request->user(),
                   'token'=> $token->plainTextToken
               ]
               ],200
        );   
    }

    public function logout(Request $request) {

        $request->user()->tokens()->delete();

        return response()->json(
            [
                'message' => 'Seccion Cerrada Exitosamente'
            ],200
        );

    }
}