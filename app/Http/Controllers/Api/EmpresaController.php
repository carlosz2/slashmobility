<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresas;
use App\Http\Resources\Empresa as EmpresaResource;
class EmpresaController extends Controller
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
        $request->validate([
            "nombre_empresa" => "required",
            "direccion" => "required",
            "telefono" => "required",
            "ciudad" => "required",
        ]);
        // Tenemos que traer el id del usuario logueado
       
        $Empresa = new Empresas();    
        $Empresa->nombre_empresa = $request->nombre_empresa;
        $Empresa->direccion = $request->direccion;
        $Empresa->telefono = $request->telefono;
        $Empresa->ciudad = $request->ciudad;
        $Empresa->save();
        //response
        return response()->json([
            $Empresa,
            "msg" => "Empresa creado exitosamente!"
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if( Empresas::where('id', $id)->exists() ){            
            $info = Empresas::find($id)->get();
            return response()->json([
                "msg" => $info,
            ], 200);
        }else{            
            return response()->json([
                "msg" => "No de encontró el Productos"
            ], 404);
        }
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
        if ( Empresas::where( "id", $id)->exists() ) {                        
            $empresa = Empresas::find($id);
           
            $empresa->update($request->all());
   
            return response()->json([
               "producto_atualizado"=>$empresa,
                "msg" => "Empresa actualizado correctamente."
            ],200);
        }else{
            //responde la API
            return response()->json([
                "msg" => "No de encontró el Empresa"
            ], 404);
        }
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
