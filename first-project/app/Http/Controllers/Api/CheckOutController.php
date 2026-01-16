<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CartModel;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class CheckOutController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'cart_ids' => ['required', 'array', 'min:1'],
            'cart_ids.*' => ['integer']
        ]);

        $user = $request->user();

        $carts = CartModel::with('product')
            ->whereIn('id', $request->cart_ids)
            ->where('user_id', $user->id)
            ->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'cart tidak valid'], 404);
        }

        $total = $carts->sum(fn($c) => $c->product->harga * $c->quantity); //c ada cart atau setiap item cart menghitung setiap harga dikali kuantitas

        $order = Order::create([
            'user_id' => $user->id,
            'order_code' => 'ORD-' . strtoupper(Str::random(10)),
            'total' => $total,
            'status' => 'pending'
        ]);

        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'price' => $cart->product->harga,
                'qty' => $cart->quantity,
                'subtotal' => $cart->product->harga * $cart->quantity
            ]);

            $order->load('items.product');
            MidtransConfig::$serverKey = config('midtrans.server_key');
            MidtransConfig::$isProduction = false;
            MidtransConfig::$isSanitized = true;
            MidtransConfig::$is3ds = true;
            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => $order->total,

                ],
                'item_details' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->product->id,
                        'price' => $item->price,
                        'quantity' => $item->qty,
                        'name' => $item->product->judul,
                    ];
                })->toArray(),
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ]
            ];
            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);
        }
        return response()->json([
            'snap_token' => $order->snap_token,
            'order_code' => $order->order_code
        ]);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'carts_ids' => ['required', 'array', 'min:1'],
            'carts_ids.*' => ['integer']
        ]);
        $user = $request->user();

        $carts = CartModel::with('product')
            ->whereIn('id', $request->carts_ids)
            ->where('user_id', $user->id)
            ->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'cart tidak valid'], 404);
        }

        $items = $carts->map(function ($cart) {
            return [
                'cart_id' => $cart->id,
                'product_id' => $cart->product_id,
                'product_name' => $cart->product->judul,
                'price' => $cart->product->harga,
                'qty' => $cart->quantity,
                'subtotal' => $cart->product->harga * $cart->quantity
            ];
        });

        return response()->json([
            'items' => $items,
            'total' => $carts->sum('subtotal')
        ]);
    }
}
