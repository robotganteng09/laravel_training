<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google login failed');
        }
        $email = $googleUser->getEmail();
        $name = $googleUser->getName();
        $googleID = $googleUser->getId();
        $avatar = $googleUser->getAvatar();

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'google_id' => $googleID,
                'avatar' => $avatar,
                'password' => bcrypt(str()->random(16)),
            ]
        );
        Auth::login($user);
        $token = $user->createToken('google_token')->plainTextToken;
        return redirect('login-succes?token=' . $token);
    }
}
