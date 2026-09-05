<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = [];
        foreach ($this->resource->items as $item) {
            $items[] = [
                'id' => (int) $item->id,
                'productCode' => (int) $item->product_code,
                'productName' => $item->product ? $item->product->name : null,
                'quantity' => (int) $item->quantity,
                'price' => (int) $item->price,
            ];
        }
        return [
            'id' => $this->resource->id,
            'ecommerceOrderId' => $this->resource->ecommerce_order_id,
            'customerName' => $this->resource->customer_name,
            'customerEmail' => $this->resource->customer_email,
            'totalAmount' => $this->resource->total_amount,
            'items' => $items,
            'status' => $this->resource->status,
        ];
//
//        $salesIndents = [];
//        if ($this->relationLoaded('salesIndents')) {
//            foreach ($this->salesIndents as $indent) {
//                $salesIndents[] = [
//                    'id' => (int) $indent->id,
//                    'productCode' => (int) $indent->product_code,
//                    'productName' => $indent->product ? $indent->product->name : null,
//                    'requiredQuantity' => (int) $indent->required_quantity,
//                    'orderQuantity' => (int) $indent->order_quantity,
//                    'status' => $indent->status,
//                ];
//            }
//        }
//
//        return [
//            'id' => (int) $this->id,
//            'ecommerceOrderId' => $this->ecommerce_order_id,
//            'customerName' => $this->customer_name,
//            'customerEmail' => $this->customer_email,
//            'totalAmount' => (int) $this->total_amount,
//            'status' => $this->status,
//            'items' => $items,
//            'salesIndents' => $salesIndents,
//            'createdAt' => $this->created_at ? $this->created_at->toIso8601String() : null,
//        ];
    }
}
