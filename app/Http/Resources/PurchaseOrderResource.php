<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PurchaseOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof Collection) {
            $orders = [];
            foreach ($this->resource as $order) {
                $orders[] = self::formatOrder($order);
            }
            return $orders;
        }

        return self::formatOrder($this->resource);
    }

    private static function formatOrder($order): array
    {
        $items = [];
        if ($order->relationLoaded('items')) {
            foreach ($order->items as $item) {
                $items[] = [
                    'id' => (int) $item->id,
                    'productCode' => (int) $item->product_code,
                    'productName' => $item->product ? $item->product->name : null,
                    'quantity' => (int) $item->quantity,
                ];
            }
        }

        return [
            'id' => (int) $order->id,
            'poNumber' => $order->po_number,
            'purchaseIndentId' => (int) $order->purchase_indent_id,
            'status' => $order->status,
            'ecommerceOrderId' => $order->ecommerce_order_id,
            'items' => $items,
            'purchaseIndent' => $order->relationLoaded('purchaseIndent') && $order->purchaseIndent ? [
                'id' => (int) $order->purchaseIndent->id,
                'indentNumber' => $order->purchaseIndent->indent_number,
                'status' => $order->purchaseIndent->status,
            ] : null,
            'createdAt' => $order->created_at ? $order->created_at->toIso8601String() : null,
        ];
    }
}
