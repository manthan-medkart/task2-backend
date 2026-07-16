<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Termwind\Components\Dd;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->resource instanceof Collection)
        {
            $products = [];
            foreach($this->resource as $product ) {
                $products[] = [
                    'id' => $product->id,
                    'productCode' => (int) $product->product_code,
                    'name' => $product->name,
                    'composition' => $product->composition,
                    'mrp' => $product->mrp,
                    'salesRate' => $product->sale_rate,
                    'totalStrip' => $product->total_strip,
                    'medicinePerStrip' => $product->medicine_per_strip,
                    'imageUrl' => $product->image_url,
                    'availability' => $product->availability
                ];
            }
            return $products;
        }
        else return [
            'id' => (int) $this->id,
            'productCode' => (int) $this->product_code,
            'name' => $this->name,
            'composition' => $this->composition,
            'mrp' => $this->mrp,
            'salesRate' => $this->sale_rate,
            'totalStrip' => $this->total_strip,
            'medicinePerStrip' => $this->medicine_per_strip,
            'imageUrl' => $this->image_url,
            'availability' => $this->availability
        ];


    }
}
