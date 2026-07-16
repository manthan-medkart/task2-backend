<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Collection;

class StockLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof Collection) {
            $logs = [];
            foreach ($this->resource as $log) {
                $logs[] = [
                    'id' => (int) $log->id,
                    'productCode' => (int) $log->product_code,
                    'quantityChange' => (int) $log->quantity_change,
                    'type' => $log->type,
                    'description' => $log->description,
                    'createdAt' => $log->created_at ? $log->created_at->toIso8601String() : null,
                ];
            }
            return $logs;
        }

        return [
            'id' => (int) $this->id,
            'productCode' => (int) $this->product_code,
            'quantityChange' => (int) $this->quantity_change,
            'type' => $this->type,
            'description' => $this->description,
            'createdAt' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
