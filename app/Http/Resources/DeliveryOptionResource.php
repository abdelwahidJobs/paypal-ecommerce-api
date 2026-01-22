<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryOptionResource  extends JsonResource
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
            "deliveryDays" => $this->name,
            "priceCents" => $this->price_cents,
            "createdAt" => $this->created_at,
            "updatedAt" => $this->updated_at
        ];
    }
}
