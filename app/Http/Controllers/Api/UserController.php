<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\User as UserResource;
use GrahamCampbell\ResultType\Result;
use Illuminate\Validation\Rule;
class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $this->sendResponse(UserResource::collection($users), 'Usuarios recuperados.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = user::find($id);
        if (is_null($user)) {
            return $this->sendError('Usuario no registrado.');
        }
        return $this->sendResponse(new UserResource($user), 'Usuario recuperado.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {      
   
         $user = User::find($request->user()->id);
         
         $v = Validator::make($request->all(), [
            'email' => 'sometimes|required|email|unique:users',
            'username' => 'sometimes|required|username|unique:users',
        ]);
         if($v->fails()){
            return $this->sendError($v->errors());       
        }
        $user->update($request->all());
        return $this->sendResponse(new userResource($user), 'Usuario Actualizado.');

    }

     /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        if(User::where('username','like','%'.$name.'%')->get() ){            
            $info = User::where('username','like','%'.$name.'%')->get();
            $response = [
                'success' => true,
                'data'    => $result,
                'message' => "Productos Encontrados",
            ];
    
            return response()->json($response, 200);
            }else{            
                $response = [
                    'success' => false,
                    'message' => "No se encontraron los Productos",
                ];  
                return response()->json($response, 404);
            }
        }
    }
}
