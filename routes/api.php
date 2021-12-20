<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\NewPasswordController;
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

Route::middleware('auth:sanctum','verify')->get('/user', function (Request $request) {
    return $request->user();  
    Route::post('logout', [AuthController::class, "logout"]);
    Route::get("user-profile/{id}", [AuthController::class, "userProfile"]);
    Route::get("listaUsuarios", [AuthController::class, "listaUsuarios"]);
    Route::post('reset-password', [NewPasswordController::class, 'reset']);
    //rutas para Productos    
    Route::post("create-producto", [BlogController::class, "createProducto"]); 
    Route::get("list-Productos", [BlogController::class, "listaProductos"]); 
    Route::get("show-Producto/{id}", [BlogController::class, "showProducto"]); 
    Route::get("TipoProducto/{tipo}", [BlogController::class, "TipoProducto"]); 
    Route::get("ListaProductoEnviados/{ciudad}", [BlogController::class, "ListaProductoEnviados"]); 
    Route::put("update-Producto/{id}", [BlogController::class, "updateProducto"]); 
     //rutas para Empresas    
     Route::post("create-empresa", [BlogController::class, "createEmpresa"]); 
     Route::get("list-empresa", [BlogController::class, "listaEmpresas"]); 
     Route::get("show-empresa/{id}", [BlogController::class, "showEmpresa"]); 
     Route::put("update-empresa/{id}", [BlogController::class, "updateEmpresa"]);
   
});

Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@login');

Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
