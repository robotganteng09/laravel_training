<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CartController extends Controller
{
    public function index(){

        // if (Gate::danies('customer')) {
        //     return response()->json(['error' => 'hanya costumer yang bisa menambahkan produk ke keranjang'], 403);
        // }

        $user = Auth::user();
        $carts = CartModel::with('product')->where('user_id'.$user->id)->get();
        return response()->json($carts);
    }

    public function store(Request $request){
        $user = Auth::user();
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'nullable|integer|min:1',
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

        return response()->json([
            'message' => 'Produk berhasil menambahkan ke cart',
            'data' => 'cart'
        ]);
    }

    public function update(Request $request, CartModel $cart)
    {
        // if (Gate::danies('customer')) {
        //     return response()->json(['error' => 'hanya customer yang bisa menambahkan produk ke keranjang'], 403);
        // }

        $user = Auth::user();
        if ($cart->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        $cart->update(['quantity' => $request->quantity]);
        return response()->json([
            'message' => 'Cart berhasil diperbarui',
            'data'    => $cart
        ]);
    }
    public function destroy(CartModel $cart)
    {
        // if (Gate::danies('costumer')) {
        //     return response()->json(['error' => 'hanya customer yang bisa menambahkan produk ke keranjang'], 403);
        // }

        $user = Auth::user();
        if ($cart->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $cart->delete();
        return response()->json(['message' => 'Produk berhasil dihapus dari cart']);
    }
}
