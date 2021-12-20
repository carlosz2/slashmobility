<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//usamos el modelo Producto
use App\Models\Producto;
use App\Models\Empresa;
class ProductoController extends Controller
{    
    // Crear un Producto
    public function createProducto(Request $request) {
        //validacion
        $request->validate([
            "nombre" => "required",
            "tipo" => "required",
            "descripcion" => "required",
            "imagen" => "required",
            "nombre_empresa" => "required",
        ]);
       
        
        $producto = new Producto();    
        $producto->id = $request->id; 
        $producto->nombre = $request->nombre;
        $producto->tipo = $request->tipo;
        $producto->descripcion = $request->descripcion;
        $producto->imagen = $request->imagen;
        $producto->nombre_empresa = $request->nombre_empresa;
        $producto->save();
        //response
        return response([
            "status" => 1,
            "msg" => "¡producto creado exitosamente!"
        ]);
    }

    // Muestra TODOS los blogs de UN USUARIO en particular
    public function listaProdctos() {
        $Productos = Producto::all();
        return response()->json($Productos);
    }
    
    public function showProducto($id) {
        
        if( Producto::where('id', $id)->exists() ){            
            $info = Producto::where('id', $id)->get();
            return response()->json([
                "status" => 1,
                "msg" => $info,
            ], 200);
        }else{            
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Productos"
            ], 404);
        }
    }
    public function TipoProducto($tipo) {
        
        if( Producto::where('tipo', $tipo)->exists() ){            
            $info = Producto::where('tipo', $tipo)->get();
            return response()->json([
                "status" => 1,
                "msg" => $info,
            ], 200);
        }else{            
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Productos"
            ], 404);
        }
    }
    public function ListaProductoEnviados($ciudad) {
        if( Empresa::where('ciudad', $ciudad)->exists() ){ 
        $info = Producto::select('Productos.nombre', 'Productos.imagen','Empresas.ciudad')
                ->join('Empresas', 'Productos.nombre_empresa', '=', 'Empresas.nombre_empresa')
                ->get();

      
            return response()->json([
                "status" => 1,
                "msg" => $info,
            ], 200);
        }else{            
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Productos"
            ], 404);
        }
    }

    public function updateProductos(Request $request){
        if ( Producto::where( "id", $request->id )->exists() ) {                        
            $Producto = Producto::find($request->id);
            
            $Producto->nombre = isset($request->nombre) ? $request->nombre :  $Producto->nombre;    
            $Producto->tipo = isset($request->tipo) ? $request->tipo : $Producto->tipo;                
            $Producto->descripcion = isset($request->descripcion) ? $request->descripcion : $Producto->descripcion;    
            $Producto->imagen = isset($request->imagen) ? $request->imagen : $Producto->imagen;
            $Producto->nombre_empresa = isset($request->nombre_empresa) ? $request->nombre_empresa : $Producto->nombre_empresa;    
            
            $Producto->save();
            //respuesta
            return response()->json([
                "status" => 1,
                "msg" => "Producto actualizado correctamente."
            ]);
        }else{
            //responde la API
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Producto"
            ], 404);
        }
    }

    
}
