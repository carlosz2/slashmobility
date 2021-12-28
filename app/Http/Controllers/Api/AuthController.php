<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User as UserResource;
use App\Http\Controllers\API\BaseController as BaseController;


class AuthController extends BaseController
{
    public function register(Request $request)
{       $input = $request->all();
        $validator = Validator::make($input, [
            
            'email' => 'required',
            'username' => 'required',
            'password' => 'required',            
        ]);
        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
        // Creamos al usuario

     
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        $success['username'] =  $user->username;
        return $this->sendResponse($success,'Usuario creado con éxito.'); 
    }

    public function login(LoginRequest $request) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
        $authUser = Auth::user();
        $request->authenticate();
        $success['token'] =  $request->user()->createToken('authtoken')->plainTextToken;
        $success['nombre'] =  $authUser->nombre;
     
        return $this->sendResponse($success, 'User signed in');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
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