<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class PurchaseIndentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof Collection) {
            $indents = [];
            foreach ($this->resource as $indent) {
                $indents[] = self::formatIndent($indent);
            }
            return $indents;
        }

        return self::formatIndent($this->resource);
    }

    private static function formatIndent($indent): array
    {
        $items = [];
        if ($indent->relationLoaded('items')) {
            foreach ($indent->items as $item) {
                $items[] = [
                    'id' => (int) $item->id,
                    'productCode' => (int) $item->product_code,
                    'productName' => $item->product ? $item->product->name : null,
                    'quantity' => (int) $item->quantity,
                    'salesIndentId' => $item->sales_indent_id ? (int) $item->sales_indent_id : null,
                ];
            }
        }

        return [
            'id' => (int) $indent->id,
            'indentNumber' => $indent->indent_number,
            'status' => $indent->status,
            'items' => $items,
            'purchaseOrder' => $indent->relationLoaded('purchaseOrder') && $indent->purchaseOrder ? [
                'id' => (int) $indent->purchaseOrder->id,
                'poNumber' => $indent->purchaseOrder->po_number,
                'status' => $indent->purchaseOrder->status,
            ] : null,
            'createdAt' => $indent->created_at ? $indent->created_at->toIso8601String() : null,
        ];
    }
}
