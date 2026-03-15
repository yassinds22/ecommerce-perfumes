<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'slug' => $this->slug,
            'logo_url' => $this->getFirstMediaUrl('logos'),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
