<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\Empresas;
use App\Http\Resources\Producto as ProductoResource;
use App\Http\Controllers\API\BaseController as BaseController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ProductoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Productos = Productos::all();
        return $this->sendResponse(ProductoResource::collection($Productos), 'Productos Encontrados.');
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
            "nombre" => "required",
            "tipo" => "required",
            "descripcion" => "required",
            "imagen" => "required",
            "nombre_empresa" => "required",
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
      
        if($request->hasFIle('imagen')){
            $producto = new Productos();
            $nombreOriginal =$request->file('imagen')->getClientOriginalName();
            $nuevoNombre=Carbon::now()->timestamp."_".$nombreOriginal;
            $carpetaDestino='./upload/';
            $request->file('imagen')->move($carpetaDestino,$nuevoNombre);
            $producto->imagen = ltrim($carpetaDestino,'.').$nuevoNombre;
            $producto->nombre = $request->nombre;
            $producto->tipo = $request->tipo;
            $producto->descripcion = $request->descripcion;
            $producto->nombre_empresa =$request->nombre_empresa;
            $producto->save();
        }
           
        return $this->sendResponse(new ProductoResource($producto), 'Producto creado.');
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $producto = Productos::find($id);
        if (is_null($producto)) {
            return $this->sendError('Producto no se encuentra registrado.');
        }
        return $this->sendResponse(new ProductoResource($producto), 'Producto encontrado.');
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
        $producto = Productos::find($id);
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required',
            'tipo' => 'sometimes|required',
            'descripcion' => 'sometimes|required',
            'imagen' => 'sometimes|required',
            'nombre_empresa' => 'sometimes|required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());       
        }
        $producto->update($request->all());
        return $this->sendResponse(new ProductoResource($producto), 'Producto Actualizado.');
        $producto->update($request->all());
   
         
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
