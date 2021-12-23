<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    protected $table = 'empresas' ;

    protected $fillable = [
        'id',
        'nombre_empresa',
        'direccion',
        'telefono',   
        'ciudad',          
    ];

    public $timestamps = false;
}
