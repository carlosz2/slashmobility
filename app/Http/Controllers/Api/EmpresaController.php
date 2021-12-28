<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Http\Resources\Empresa as EmpresaResource;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends BaseController
{    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Empresas = Empresas::all();
        return $this->sendResponse(EmpresaResource::collection($Empresas), 'Usuarios recuperados.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            "nombre_empresa" => "required",
            "direccion" => "required",
            "telefono" => "required",
            "ciudad" => "required",
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $empresa = Empresas::create($input);
        return $this->sendResponse(new EmpresaResource($empresa), 'Empresa creada.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empresa = Empresas::find($id);
        if (is_null($empresa)) {
            return $this->sendError('Empresa no registrada.');
        }
        return $this->sendResponse(new EmpresaResource($empresa), 'Empresa encontrada.');
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {        
       
            $empresa = Empresas::find($id);
          
            $validator = Validator::make($request->all(), [
                'nombre_empresa' => 'sometimes|required',
                
            ]);
            if($validator->fails()){
                return $this->sendError($validator->errors());       
            }
            $empresa->update($request->all());
            return $this->sendResponse(new EmpresaResource($empresa), 'Empresa Actualizada.');
    }
    
         /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name) {
         
        if(Empresas::where('nombre_empresa','like','%'.$name.'%')->get() ){            
            $info = Empresas::where('nombre_empresa','like','%'.$name.'%')->get();
            return response()->json([
                "msg" => $info,
            ], 200);
        }else{            
            return response()->json([
                "msg" => "No de encontró el Empresa"
            ], 404);
        }
    }

}
