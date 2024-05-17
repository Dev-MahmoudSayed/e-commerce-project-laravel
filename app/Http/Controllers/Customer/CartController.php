<?php

namespace App\Http\Controllers\Customer;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\cart\CartResource;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $product_id = $request->product_id;
        $cart= auth()->user()->cart;
        $cartItem= $cart->items()->where('product_id',$product_id)->first();

       return response()->json($cartItem);

    }


    public function create()
    {
        //
    }


    public function store(Request $request )
    {
       $user_id = Auth::user()->id;
       $product_id = $request->product_id;
       $quantity = $request->quantity;
       $product = Product::where('id',$product_id)->where('quantity','>',0)->exists();
       $cart= auth()->user()->cart;
       if(is_null($cart)){
        $cart=auth()->user()->cart()->create([]);
       }
       $cart->items;
       $cartItem = $cart->items()->where('product_id',$product_id)->first();
       if($product)
       {
        if($cartItem)
        {
            $cartItem->update(['quantity'=>$cartItem->quantity + $quantity]);

            return new CartResource($cartItem);
        }else{
            $cart = new Cart();
             $cart->user_id =$user_id;

             $cart->save();

            $cartItem = new CartItem();
            $cartItem->cart_id = $user_id;
            $cartItem->product_id = $product_id;
            $cartItem->quantity = $quantity;
            $cartItem->save();
            $product = Product::where('quantity','>',1);
            if($product)
            {
                $productQuantity = auth()->user()->cart->items->product['quantity'];
                $productQuantity->update(['quantity'=> $quantity - $cartItem->quantity ]);
            }
            return new CartResource($cartItem);
        }
       }else
       {
        return response()->json(['msg'=>'product not found']);
       }

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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $cartItem =CartItem::find($id);
        $cartItem->delete();
        return response()->json(['msg'=>'cart deleted']);
    }
}
