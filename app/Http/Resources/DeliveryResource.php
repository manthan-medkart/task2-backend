<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'salesOrderId' => (int) $this->sales_order_id,
            'deliveryNumber' => $this->delivery_number,
            'status' => $this->status,
            'trackingNumber' => $this->tracking_number,
            'createdAt' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
