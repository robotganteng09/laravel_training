<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\api\ProfileController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('/products', ProductController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/profile',[ProfileController::class,'store']);
});


