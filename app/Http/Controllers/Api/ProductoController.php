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
            $result = Productos::where('nombre','like','%'.$name.'%')->get();
            	$response = [
            'success' => true,
            'data'    => $result,
            'message' => "Productos Encontrados",
        ];

        return response()->json($response, 200);
        }else{            
            $response = [
                'success' => false,
                'message' => "Nose encontraron los Productos",
            ];  
            return response()->json($response, 404);
        }
    }


    public function TipoProducto($tipo) {
        
        if( Productos::where('tipo', $tipo)->exists() ){            
            $result = Productos::where('tipo', $tipo)->get();
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
    public function ProdcutosCidudad($ciudad) {
        if( Empresas::where('ciudad', $ciudad)->exists() ){ 
            $result = Empresas::where('ciudad', $ciudad)
            ->join('productos', 'Empresas.nombre_empresa', '=', 'productos.nombre_empresa')
            ->select('*')->get();
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
