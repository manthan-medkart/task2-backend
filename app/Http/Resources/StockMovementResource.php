<?php

namespace App\Http\Resources;

use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class StockMovementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->resource instanceof Collection){
            $stockMovementsList = [];
            foreach($this->resource as $stockMovement){
                $stockMovementsList[] = [
                    'id' => $stockMovement->id,
                    'productCode' => $stockMovement->product_code,
                    'movementType' => $stockMovement->movement_type,
                    'quantityChange' => $stockMovement->quantity_change,
                    'beforeQuantity' => $stockMovement->before_quantity,
                    'afterQuantity' => $stockMovement->after_quantity,
                    'source' => $stockMovement->source,
                    'updated_by' => $stockMovement->updated_by,
                ];
            }
            return $stockMovementsList;
        }
        return [
            'id' => $this->id,
            'productCode' => $this->product_code,
            'movementType' => $this->movement_type,
            'quantityChange' => $this->quantity_change,
            'beforeQuantity' => $this->before_quantity,
            'afterQuantity' => $this->after_quantity,
            'source' => $this->source,
            'updated_by' => $this->updated_by,
        ];
    }
}
