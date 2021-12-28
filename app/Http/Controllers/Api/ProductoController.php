<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\Empresas;
use Carbon\Carbon;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Productos = Productos::all();
        return response()->json($Productos);
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
            "nombre" => "required",
            "tipo" => "required",
            "descripcion" => "required",
            "imagen" => "required",
            "nombre_empresa" => "required",
        ]);
       
        
        $producto = new Productos();
        if($request->hasFIle('imagen')){
            $nombreOriginal =$request->file('imagen')->getClientOriginalName();
            $nuevoNombre=Carbon::now()->timestamp."_".$nombreOriginal;
            $carpetaDestino='./upload/';
            $request->file('imagen')->move($carpetaDestino,$nuevoNombre);
            $producto->imagen = ltrim($carpetaDestino,'.').$nuevoNombre;
           
        $producto->nombre = $request->nombre;
        $producto->tipo = $request->tipo;
        $producto->descripcion = $request->descripcion;
        
        $producto->nombre_empresa =$request->nombre_empresa;;
        
        $producto->save();
        }
           
        
        //response
        return response([
            "producto_creado" => $producto,
            "msg" => "¡producto creado exitosamente!"
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
        if( Productos::where('id', $id)->exists() ){            
            $info = Productos::find($id)->get();
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
        if ( Productos::where( "id", $id)->exists() ) {                        
            $producto = Productos::find($id);
           
            $producto->update($request->all());
   
            return response()->json([
               "producto_atualizado"=>$producto,
                "msg" => "Producto actualizado correctamente."
            ],200);
        }else{
            //responde la API
            return response()->json([
                "msg" => "No de encontró el Producto"
            ], 404);
        }
    }
    
    /**
     * Search for a name
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        if(Productos::where('nombre','like','%'.$name.'%')->get() ){            
            $info = Productos::where('nombre','like','%'.$name.'%')->get();
            return response()->json([
                "msg" => $info,
            ], 200);
        }else{            
            return response()->json([
                "msg" => "No de encontró el Usuario"
            ], 404);
        }
    }


    public function TipoProducto($tipo) {
        
        if( Productos::where('tipo', $tipo)->exists() ){            
            $info = Productos::where('tipo', $tipo)->get();
            return response()->json([
                "msg" => $info,
            ], 200);
        }else{            
            return response()->json([
                "msg" => "No de encontró Productos con ese Tipo de Productos"
            ], 404);
        }
    }
    public function ProdcutosCidudad($ciudad) {
        if( Empresas::where('ciudad', $ciudad)->exists() ){ 
        $info = Productos::select('productos.nombre', 'productos.imagen','empresas.ciudad')
                ->join('empresas', 'productos.nombre_empresa', '=', 'empresas.nombre_empresa')
                ->get();

      
            return response()->json([
                "msg" => $info,
            ], 200);
        }else{            
            return response()->json([
                "status" => 0,
                "msg" => "No se encontró Productos"
            ], 404);
        }
    }
}
