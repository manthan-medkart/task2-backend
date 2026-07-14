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
            'mrp' => $this->mrp,
            'sales_rate' => $this->sales_rate,
            'total_strip' => $this->total_strip,
            'medicine_per_strip' => $this->medicine_per_strip,
            'image_url' => $this->image_url
        ];
    }
}
