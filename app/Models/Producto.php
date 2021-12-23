<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
class Producto extends Model
{
    use HasFactory , HasApiTokens;

    protected $table = 'productos' ;

    protected $fillable = [
        'id',
        'nombre',
        'tipo',
        'descripcion',   
        'imagen',
        'empresa_id',          
    ];

    public $timestamps = false;

}
