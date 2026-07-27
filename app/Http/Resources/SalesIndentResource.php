<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class SalesIndentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'salesOrderId' => (int) $this->sales_order_id,
            'productCode' => (int) $this->product_code,
            'productName' => $this->product ? $this->product->name : null,
            'requiredQuantity' => (int) $this->quantity,
            'orderQuantity' => (int) $this->quantity,
            'status' => $this->status,
            'salesOrder' => null,
            'createdAt' => $this->created_at ? $this->created_at->toIso8601String() : null,

        ];
    }

}
