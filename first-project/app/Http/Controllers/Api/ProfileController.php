<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'namaLengkap' => 'required|string',
            'alamat' => 'required|string'
        ]);

        $user = Auth::user();
        // return response()-> json([$user->id]);
        if ($user->profile) {
            return response()->json(['message' => 'Profile already exist'], 400);
        }
        $profile = Profile::create([
            'user_id' => $user->id,
            'namaLengkap' => $request->namaLengkap,
            'alamat' => $request->alamat
        ]);

        return response()->json($profile, 201);
    }

    public function update(Request $request){
        $request->validate([
            'namaLengkap' => 'required|string',
            'alamat' => 'required|string'
        ]);

        $user = Auth::user();

        $profile = $user -> profile;
        if(!$profile){
            return response() -> json( 'Profile not found' ['message']);
        }
        $profile ->update($request -> only([
            'namaLengkap',
            'alamat'
        ]));
        return response()->([
            'message' => 'profile updated succesfully',
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}
