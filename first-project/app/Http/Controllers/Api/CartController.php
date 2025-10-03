<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(){
        $user = Auth::user();
        $carts = CartModel::with('product')->where('user_id'.$user->id)->get();
        return response()->json($carts);
    }

    public function store(Request $request){
        $user = Auth::user();
        $request->validate([
            'product_id' => 'required|exist:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $cart = CartModel::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $request->product_id
            ],
            [
                'quantity' => $request->quantity?? 1,
            ]
        );
    }
}
