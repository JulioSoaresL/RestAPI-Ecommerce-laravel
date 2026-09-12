<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id'   => $this->product->id,
                    'name' => $this->product->name,
                    'slug' => $this->product->slug ?? null,
                ];
            }),
            'quantity' => (int) $this->quantity,
            'unit_price' => (float) $this->price,
            'subtotal' => (float) ($this->price * $this->quantity),
        ];
    }
}
