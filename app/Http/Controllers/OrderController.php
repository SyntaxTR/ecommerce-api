<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
class OrderController extends Controller
{

    public function store(Request $request)
    {
        $user = $request->user();
        // Active Cart
        $cart = Cart::where('user_id', $user->id)->where('status', 'active')->first();
        if (!$cart) {
            return response()->json(['message' => 'Cart not found.'], 404);
        }

        // Cart Items
        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 400);
        }

        // Calc. order total and control stock
        $totalAmount = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;

            // Stock control
            if ($product->stock < $item->quantity) {
                return response()->json([
                    'message' => "Product: {$product->name}, no stock. Avaiable Stock: {$product->stock}."
                ], 400);
            }

            // Decrement stock
            $product->decrement('stock', $item->quantity);

            $totalAmount += $item->quantity * $item->price;
        }

        // Create Order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // Close cart
        $cart->update(['status' => 'completed']);

        return response()->json([
            'message' => 'Order successfully created.',
            'order' => $order,
        ], 201);
    }
    public function index(Request $request) {
        return $request->user()->orders;
    }

    public function show(Request $request, $id) {
        return $request->user()->orders()->find($id);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::find($id);


        if (!$order) {
            return response()->json(['message' => 'Order Not found.'], 404);
        }

        $user = User::find(auth()->id());

        // Only creater user or admin user can update status
        if ($order->user_id !== $request->user()->id && $user->is_admin == "F") {
            return response()->json(['message' => 'You cant change the order status.'], 403);
        }

        if($order->user_id == $request->user()->id && $user->is_admin == "F" && $request->status !== "cancelled"){

            if($order->status !== "pending"){
                return response()->json(['message' => "You cannot change the order status because order is $order->status."], 403);
            }else if($request->status !== "cancelled"){
                return response()->json(['message' => 'You can only cancel the order.'], 403);
            }
        }


        // Update Status
        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Order status successfully updated.',
            'order' => $order,
        ]);
    }

}
