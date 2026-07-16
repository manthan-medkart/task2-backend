<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ApiResponse;
use App\Http\Requests\UpdateStockRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StockLogResource;
use App\Http\Services\StockService;
use Exception;

class StockController extends Controller
{
    private StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function updateStock(int $productCode, UpdateStockRequest $request)
    {
        try {
            $product = $this->stockService->updateStock($productCode, $request->validated());

            return ApiResponse::success(
                'Stock updated successfully',
                200,
                new ProductResource($product)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Stock update failed',
                400,
                $e->getMessage()
            );
        }
    }

    public function getStockHistory(int $productCode)
    {
        try {
            $history = $this->stockService->getStockHistory($productCode);

            return ApiResponse::success(
                'Stock history retrieved successfully',
                200,
                new StockLogResource($history)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve stock history',
                400,
                $e->getMessage()
            );
        }
    }
}
