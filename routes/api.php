<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use \App\Http\Middleware\Authenticate;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\NewPasswordController;
use \Illuminate\Auth\Middleware\EnsureEmailIsVerified;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group( ['middleware'=> ["auth:sanctum"] ], function() {

    //rutas para Usuarios
    
    Route::get('logout', [AuthController::class, "logout"]);
    Route::get("user-profile/{id}", [AuthController::class, "userProfile"]);
    Route::get("listaUsuarios", [AuthController::class, "listaUsuarios"]);
    Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
    Route::post('reset.password', [NewPasswordController::class, 'reset']);
    
    //rutas para Productos    
    Route::post("create-producto", [ProductoController::class, "createProducto"]); 
    Route::get("list-Productos", [ProductoController::class, "listaProductos"]); 
    Route::get("show-Producto/{id}", [ProductoController::class, "showProducto"]); 
    Route::get("TipoProducto/{tipo}", [ProductoController::class, "TipoProducto"]); 
    Route::get("ListaProductoEnviados/{ciudad}", [ProductoController::class, "ListaProductoEnviados"]); 
    Route::put("update-Producto/{id}", [ProductoController::class, "updateProducto"]); 
     //rutas para Empresas    
     Route::post("create-empresa", [EmpresaController::class, "createEmpresa"]); 
     Route::get("list-empresa", [EmpresaController::class, "listaEmpresas"]); 
     Route::get("show-empresa/{id}", [EmpresaController::class, "showEmpresa"]); 
     Route::put("update-empresa/{id}", [EmpresaController::class, "updateEmpresa"]);
    });
 //rutas para Usuarios
 Route::post('login', [AuthController::class, "login"]);
 Route::post('register', [AuthController::class, 'register']);
 //Route::post('reset-password', [NewPasswordController::class, 'reset']);
Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');



Route::get('/email/verify', function () {

    return view('welcome');
})->middleware('auth')->name('verification.notice');


