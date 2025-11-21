<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/products', [ProductController::class, 'store'])->middleware('can:admin-only');
    Route::put('/products/{id}', [ProductController::class, 'update']) -> middleware('can:admin-only');
    Route::delete('/products/{id}', [ProductController::class, 'destroy']) -> middleware('can:admin-only');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'profile']);
    Route::post('/profile',[ProfileController::class,'store']);
    Route::put('/profile',[ProfileController::class,'update']);
    Route::delete('/profile',[ProfileController::class,'destroy']);
    Route::get('/carts', [CartController::class, 'index']); //->middleware('can:customer');
    Route::post('/carts', [CartController::class, 'store']); //->middleware('can:customer');
    Route::put('/carts/{cart}', [CartController::class, 'update']); //->middleware('can:customer');
    Route::delete('/carts/{cart}', [CartController::class, 'destroy']); //->middleware('can:customer');
 
});


