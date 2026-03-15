<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'total' => (float) $this->total,
            'shipping_cost' => (float) $this->shipping_cost,
            'tax' => (float) $this->tax,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'address_details' => $this->address_details,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at->toDateTimeString(),
            'shipped_at' => $this->shipped_at ? $this->shipped_at->toDateTimeString() : null,
            'delivered_at' => $this->delivered_at ? $this->delivered_at->toDateTimeString() : null,
        ];
    }
}
