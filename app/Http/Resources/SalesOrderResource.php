<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = [];
        if ($this->relationLoaded('items')) {
            foreach ($this->items as $item) {
                $items[] = [
                    'id' => (int) $item->id,
                    'productCode' => (int) $item->product_code,
                    'quantity' => (int) $item->quantity,
                    'price' => (int) $item->price,
                ];
            }
        }

        return [
            'id' => (int) $this->id,
            'ecommerceOrderId' => $this->ecommerce_order_id,
            'customerName' => $this->customer_name,
            'customerEmail' => $this->customer_email,
            'totalAmount' => (int) $this->total_amount,
            'status' => $this->status,
            'items' => $items,
            'invoice' => new SalesInvoiceResource($this->whenLoaded('invoice')),
            'delivery' => new DeliveryResource($this->whenLoaded('delivery')),
            'createdAt' => $this->created_at ? $this->created_at->toIso8601String() : null,
        ];
    }
}
