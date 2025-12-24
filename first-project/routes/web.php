<?php

use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Socialite;

Route::get('/', function () {
    return view('page.index');
});
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/login-success', function () {
    return view('auth.login-success');
})->name('register');
Route::get('/mycart', function () {
    return view('page.cart');
})->name('mycart');
Route::get('/auth/google/redirect',[GoogleAuthController::class,'redirect'])->name('google.redirect');
Route::get('/auth/google/callback',[GoogleAuthController::class,'callback'])->name('google.callback');

