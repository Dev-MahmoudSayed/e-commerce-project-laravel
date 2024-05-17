<?php

namespace App\Http\Resources\cart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id'=>$this->product_id,
            'quantity'=>$this->quantity,
            'cart_id'=>$this->cart_id,
        ];
    }
}
