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
    Route::post('logout', 'App\Http\Controllers\UserController@logout');
    Route::get("user-profile", [UserController::class, "userProfile"]);
    Route::get("listaUsuarios", [UserController::class, "listaUsuarios"]);

    //rutas para Blog    
    Route::post("create-productos", [BlogController::class, "createProductos"]); 
    Route::get("list-Productos", [BlogController::class, "listProductos"]); 
    Route::get("show-Producto/{id}", [BlogController::class, "showProducto"]); 

    Route::delete("delete-Producto/{id}", [BlogController::class, "deleteProducto"]);
    Route::put("update-Producto/{id}", [BlogController::class, "updateProducto"]); 
    Route::post('reset-password', [NewPasswordController::class, 'reset']);
});

Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@login');


Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
