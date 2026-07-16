<?php

namespace App\Http\Services;

use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Update stock level for a product and record the transaction.
     *
     * @param int $productCode
     * @param array $data
     * @return Product
     * @throws \Exception
     */
    public function updateStock(int $productCode, array $data): Product
    {
        return DB::transaction(function () use ($productCode, $data) {
            $product = Product::where('product_code', $productCode)->first();
            if (!$product) {
                throw new ModelNotFoundException("Product with code {$productCode} not found.");
            }

            $quantity = (int) $data['quantity'];
            $type = $data['type'];
            $description = $data['description'] ?? null;
            $quantityChange = 0;

            if ($type === 'addition') {
                $quantityChange = $quantity;
                $product->total_strip += $quantity;
            } elseif ($type === 'deduction') {
                if ($product->total_strip < $quantity) {
                    throw new \Exception("Insufficient stock. Current stock: {$product->total_strip}");
                }
                $quantityChange = -$quantity;
                $product->total_strip -= $quantity;
            } elseif ($type === 'adjustment') {
                // For adjustment, quantity represents the target stock level
                $quantityChange = $quantity - $product->total_strip;
                $product->total_strip = $quantity;
            }

            // Save the updated stock level on product
            $product->save();

            // Record the stock log transaction
            StockLog::create([
                'product_code' => $product->product_code,
                'quantity_change' => $quantityChange,
                'type' => $type,
                'description' => $description,
            ]);

            return $product;
        });
    }

    /**
     * Get stock change logs/history for a specific product.
     *
     * @param int $productCode
     * @return Collection
     */
    public function getStockHistory(int $productCode): Collection
    {
        $product = Product::where('product_code', $productCode)->first();
        if (!$product) {
            throw new ModelNotFoundException("Product with code {$productCode} not found.");
        }

        return $product->stockLogs()->orderBy('created_at', 'desc')->get();
    }
}
