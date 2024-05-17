<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'address'=>$this->address,
            'client_phone'=>$this->client_phone,
            'city'=>$this->city,
            'province'=>$this->province,
            'product_name'=>$this->product_name,
            'price'=>$this->price,
           
            'status'=>$this->status,
            'qty'=>$this->qty,
            'total'=>$this->total,

        ];
    }
}
