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
Route::get('/auth/google/redirect',[GoogleAuthController::class,'redirect'])->name('google.redirect');
Route::get('/auth/google/callback',function(){
    $googleUser = Socialite::driver('google')->user();
});
