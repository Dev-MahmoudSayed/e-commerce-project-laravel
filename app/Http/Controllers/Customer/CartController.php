<?php

namespace App\Http\Controllers\Customer;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\cart\CartResource;
use App\Http\Resources\Cart\CartCollection;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cart = auth()->user()->cart;
        $cartItems = $cart->items;
     //   return response()->json($cartItems);
        return new CartCollection($cartItems);
    }

    public function create()
    {
        //
    }


    public function store(Request $request )
    {
    $product_id = $request->product_id;
    $quantity = $request->quantity;
    $product = Product::where('id', $product_id)->where('quantity', '>', 0)->first();

    if (!$product) {
        return response()->json(['message' => 'Product not available or out of stock'], 404);
    }

    $cart = auth()->user()->cart;
    if (is_null($cart)) {
        $cart = auth()->user()->cart()->create([]);
    }

    $cartItem = $cart->items()->where('product_id', $product_id)->first();

    if ($cartItem) {
        $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
    } else {
        $cartItem = new CartItem();
        $cartItem->cart_id = $cart->id;
        $cartItem->product_id = $product_id;
        $cartItem->quantity = $quantity;
        $productAttribute = $product->attributes()->first();

        if ($productAttribute) {
            $cartItem->attribute_name = $productAttribute->attribute_name;
            $cartItem->attribute_value = $productAttribute->attribute_value;
        }

        $cartItem->save();
    }

    // Update product quantity
    $product->update(['quantity' => $product->quantity - $quantity]);

    return new CartResource($cartItem);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
      //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, $id)
    {
        $quantity = $request->quantity;

        $cart = auth()->user()->cart;
        $cartItem = $cart->items()->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->update(['quantity' => $quantity]);

        return response()->json(['message' => 'Cart item updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cart = auth()->user()->cart;
    $cartItem = $cart->items()->where('id', $id)->first();

    if (!$cartItem) {
        return response()->json(['message' => 'Cart item not found'], 404);
    }

    $cartItem->delete();

    return response()->json(['message' => 'Cart item removed successfully']);
    }
}
