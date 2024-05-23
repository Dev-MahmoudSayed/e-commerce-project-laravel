<?php

namespace App\Http\Resources\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            "name"=>$this->name,
            "description"=>$this->description,
            "price"=>$this->price,
            "image"=>$this->image,
            "quantity"=>$this->quantity,
            'attributes' => ProductAttributeResource::collection($this->whenLoaded('attributes')),
        ];
    }
}
