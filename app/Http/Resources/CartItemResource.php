<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            "productId" => $this->product_id,
            "quantity" => $this->quantity,
            "product" => new ProductResource($this->product),
            "deliveryOptionId" => $this->delivery_option_id,
            "createdAt" => $this->created_at,
            "updatedAt" => $this->updated_at
        ];
    }
}
