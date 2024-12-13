<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
                                                                                    /* N+1 ??*/
        $cart = Cart::where('user_id', auth()->id())->where('status', 'active')->with('items.product')->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        return response()->json($cart);
    }

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($validated['product_id']);

        /* Stock control */
        if ($product->stock < $validated['quantity']) {
            return response()->json(['message' => 'No stock'], 400);
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'status' => 'active']
        );

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $validated['quantity']; /* Increase quantity in cart */
            $cartItem->price = $product->price * $cartItem->quantity; /* Update price  */
            $cartItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->price * $validated['quantity'],
            ]);
        }

        return response()->json(['message' => 'Item added to cart']);
    }


    public function updateItem(Request $request, $id)
    {

        $cartItem = CartItem::find($id);

        if (!$cartItem) {
            return response()->json(['message' => 'Cart Item not found'], 404);
        }

        $cart = Cart::find($cartItem->cart_id);

        if(!$cart || $cart->user_id != auth()->id()){
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($cartItem->product_id);

        /* Stock control */
        if (!$product || $product->stock < $validated['quantity']) {
            return response()->json(['message' => 'No stock'], 400);
        }

        $cartItem->quantity = $validated['quantity']; /* Set quantity in cart */
        $cartItem->price = $product->price * $cartItem->quantity; /* Update price  */
        $cartItem->save();

        return response()->json($cartItem);
    }
    public function removeItem($id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem || $cartItem->cart->user_id != auth()->id()) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }
}
