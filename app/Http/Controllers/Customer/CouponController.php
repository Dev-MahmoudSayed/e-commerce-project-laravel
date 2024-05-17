<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderCollection;
use App\Http\Requests\Coupon\StoreCouponRequest;

class CouponController extends Controller
{
    public function applyCoupon(Request $request, $orderId)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            return response()->json(['error' => 'Invalid coupon code'], 400);
        }

        // Apply the coupon to the order...
        $order->total_price -= $coupon->discount;
        $order->save();

        return new OrderCollection($order);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::all();

        return response()->json($coupons);
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
    public function store(StoreCouponRequest $request)
    {
        $request->validated();

        $coupon = Coupon::create($request->all());

        return response()->json($coupon, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['error' => 'Coupon not found'], 404);
        }

        return response()->json($coupon);

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
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['error' => 'Coupon not found'], 404);
        }

        $request->validate([
            'code' => 'string|unique:coupons,code,' . $id,
            'discount' => 'numeric|min:0',
           
        ]);

        $coupon->update($request->all());

        return response()->json($coupon);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $coupon = Coupon::find($id);

        if (!$coupon) {
            return response()->json(['error' => 'Coupon not found'], 404);
        }

        $coupon->delete();

        return response()->json(null, 204); // 204 No Content
    }

}
