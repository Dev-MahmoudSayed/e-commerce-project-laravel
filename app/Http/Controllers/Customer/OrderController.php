<?php

namespace App\Http\Controllers\Customer;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Order\OrderCollection;

class OrderController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $days= $request->days;
        $status=$request->status;
        $recentOrders = Order::shippedWithinDays($days)->get();
        $orderStatus =  Order::status($status)->get();
        return new OrderCollection($recentOrders,$orderStatus);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

      $user =auth()->user();
      $cart = Cart::where('user_id','=',$user)->get();

            if($cart)
            {
                return DB::transaction(function () use ($request) {

                    foreach ($request->products as $requestedProduct) {
                        $product = Product::find($requestedProduct['product_id']);

                        if ($product->quantity < $requestedProduct['quantity']) {
                            return response()->json(['error' => 'Product ' . $product->id . ' does not have enough quantity'], 400);
                        }

                        // Decrement the product quantity
                        $product->decrement('quantity', $requestedProduct['quantity']);
                    }

                    $user =auth()->user();
                    $cart = Cart::where('user_id','=',$user)->get();
                    $order = Order::create([
                        'address'=>$request->address,
                        'client_phone'=>$request->client_phone,
                        'city'=>$request->city,
                        'user_id'=>auth()->user(),
                        'province'=>$request->province,
                        'client_name'=>$request->client_name,
                        'postal_code'=>$request->postal_code,
                        'status'=>'pending',
                        'qty'=>$request->qty,
                        'completed_at'=>Carbon::now()->toDateTimeString(),
                    ]);
                    OrderItem::create([
                        'order_id'=> $user->order,
                        'product_id'=> $cart->product_id,
                        'quantity'=>$cart->quantity,
                         'price'=>$request->price,
                         'total'=>$request->price * $cart->quantity,
                    ]);
                    return new OrderCollection($order);
                });
            }



    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {

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
    public function destroy(Order $order)
    {


        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json(['message' => 'Order cancelled successfully']);

    }
}
