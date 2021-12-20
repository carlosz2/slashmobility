<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//usamos el modelo Empresa
use App\Models\Empresa;

class EmpresaController extends Controller
{    
    // Crear un Empresa
    public function createEmpresa(Request $request) {
        //validacion
        $request->validate([
            "nombre" => "required",
            "direccion" => "required",
            "telefono" => "required",
            "ciudad" => "required",
        ]);
        // Tenemos que traer el id del usuario logueado
       
        $Empresa = new Empresa();    
        $Empresa->nombre = $request->nombre;
        $Empresa->direccion = $request->direccion;
        $Empresa->telefono = $request->telefono;
        $Empresa->ciudad = $request->ciudad;
        $Empresa->save();
        //response
        return response([
            "status" => 1,
            "msg" => "Empresa creado exitosamente!"
        ]);
    }

    // Muestra TODOS los blogs de UN USUARIO en particular
    public function listaEmpresas() {
        $Empresas = Empresa::all();
        return response()->json($Empresas);
    }
    
    public function showEmpresa($id) {
        
        if( Empresa::where('id', $id)->exists() ){            
            $info = Empresa::where('id', $id)->get();
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

    public function updateEmpresa(Request $request){
        if ( Empresa::where( "id", $request->id )->exists() ) {                        
            $Empresa = Empresa::find($request->id);
            
            $Empresa->nombre = isset($request->nombre) ? $request->nombre :  $Empresa->nombre;    
            $Empresa->direccion = isset($request->direccion) ? $request->direccion : $Empresa->direccion;                
            $Empresa->telefono = isset($request->telefono) ? $request->telefono : $Empresa->telefono;    
            $Empresa->ciudad = isset($request->ciudad) ? $request->ciudad : $Empresa->ciudad;   
            
            $Empresa->save();
            //respuesta
            return response()->json([
                "status" => 1,
                "msg" => "Empresa actualizado correctamente."
            ]);
        }else{
            //responde la API
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Empresa"
            ], 404);
        }
    }

}
