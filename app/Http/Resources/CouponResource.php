<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'discount_value' => (float) $this->discount, // <-- ASEGURA QUE SEA NÚMERO
            'valid_until' => $this->valid_until,
            'created_at' => $this->created_at,
            'is_active' => $this->is_active,
        ];
    }
}
