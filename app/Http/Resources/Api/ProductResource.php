<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'slug' => $this->slug,
            'description' => $this->getTranslations('description'),
            'price' => (float) $this->price,
            'sale_price' => $this->sale_price ? (float) $this->sale_price : null,
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'images' => $this->getMedia('images')->map(fn($media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
            ]),
            'sizes' => $this->whenLoaded('sizes', fn() => $this->sizes->map(fn($size) => [
                'id' => $size->id,
                'size' => $size->size,
                'price' => (float) $size->price,
            ])),
            'fragrance_notes' => $this->whenLoaded('fragranceNotes', fn() => $this->fragranceNotes->map(fn($note) => [
                'id' => $note->id,
                'name' => $note->getTranslations('name'),
                'type' => $note->pivot->type,
            ])),
            'rating' => (float) $this->reviews_avg_rating ?? 0,
            'reviews_count' => (int) $this->reviews_count ?? 0,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
