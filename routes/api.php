<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('login',[LoginController::class,'login']);
Route::post('register',[RegisterController::class,'register']);

Route::middleware('auth:api')->group(function(){

    Route::get('profile',[ProfileController::class,'show']);
    Route::post('logout',[ProfileController::class,'logout']);
    
    Route::get('products',[ProductsController::class,'index']);

});


