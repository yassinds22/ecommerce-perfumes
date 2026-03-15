<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'product_id' => $this->product_id,
            'rating' => (int) $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
