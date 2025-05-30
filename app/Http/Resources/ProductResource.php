<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'qty' => $this->qty,
            'price' => $this->price,
            'description' => $this->description,
            'category' => $this->category,
            'brand' => $this->brand,
            'color' => $this->color,
            'size' => $this->size,
            'reviews' => $this->reviews,
            'status' => $this->status,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
        ];
    }
}
