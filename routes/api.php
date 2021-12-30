<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use \App\Http\Middleware\Authenticate;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmpresaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\UserController;
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

Route::group( ['middleware'=> ['auth:sanctum'],'verified' ], function() {

    //rutas para Usuarios
    Route::resource('user', UserController::class);
    Route::put('updateuser', [UserController::class, "update"]);
    Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [NewPasswordController::class, 'reset']);
    Route::post('logout', [AuthController::class, "logout"]);

    Route::get("users/search/{name}", [EmpresaController::class, "searchEmpresa"]);
    //rutas para Productos    
    Route::resource('producto', ProductoController::class);
    Route::get("productotipo/{tipo}", [ProductoController::class, "TipoProducto"]); 
    Route::get("prodcutoscidudad/{ciudad}", [ProductoController::class, "ProdcutosCidudad"]); 
    Route::get("productos/search/{name}", [ProductoController::class, "search"]);
    //rutas para Empresas    
    Route::resource('empresa', EmpresaController::class);
    Route::get("empresas/search/{name}", [EmpresaController::class, "search"]); 
    });
 
 Route::post('login', [AuthController::class, "login"])->name('login');
 Route::post('register', [AuthController::class, 'register']);

Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');



