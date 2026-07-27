<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof Collection) {
            $stockOfAllProducts = [];
            foreach ($this->resource as $stockOfParticularProduct) {
                $stockOfAllProducts[] = new StockResource($stockOfParticularProduct);
            }
            return $stockOfAllProducts;
        }
        return [
            'product_code' => $this->product_code,
            'quantity' => $this->quantity
        ];
    }
}
