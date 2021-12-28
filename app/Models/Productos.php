<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Empresas;
class Productos extends Model
{
    use HasFactory , HasApiTokens;
     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $table = 'productos' ;
     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $fillable = [
        'id',
        'cliente',
        'nombre',
        'tipo',
        'descripcion',   
        'imagen',
        'nombre_empresa',          
    ];
  
	public function nombre_empresa()
	{	
		$nombre_empresa = Empresas::find($this->nombre_empresa);
            
		return $nombre_empresa->nombre_empresa;
	}
    
     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
       
    public $timestamps = true;

}
