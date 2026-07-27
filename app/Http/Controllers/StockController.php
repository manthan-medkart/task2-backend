<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockAdjustRequest;
use App\Http\Requests\StockCreateRequest;
use App\Http\Resources\StockResource;
use App\Http\Services\StockManagementService;

use App\Helpers\ApiResponse;
use App\Http\Requests\StockUpdateRequest;
use Exception;

class StockController extends Controller
{
    private StockManagementService $stockManagementService;

    public function __construct(StockManagementService $stockManagementService)
    {
        $this->stockManagementService = $stockManagementService;
    }

//======================================================================================================================
//================  CREATE STOCK OF NEW PRODUCT  ======================================================================================================
//======================================================================================================================

    public function createStockOfNewProduct(StockCreateRequest $request) {
        try{
            $stock = $this->stockManagementService->createStockOfNewProduct($request->validated());
            return ApiResponse::success(
                'Stock created successfully',
                200,
                new StockResource($stock)
            );
        }
        catch (Exception $e){
            return ApiResponse::error(
                'Could not create stock of new product',
                400,
                $e->getMessage()
            );
        }


    }

//======================================================================================================================
//================  ADJUST STOCK  ======================================================================================================
//======================================================================================================================

    public function adjustStock(StockAdjustRequest $request)
    {
        try {
            $stock = $this->stockManagementService->adjustStock($request->validated());

            return ApiResponse::success(
                'Stock updated successfully',
                200,
                new StockResource($stock)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Stock update failed',
                400,
                $e->getMessage()
            );
        }
    }

//======================================================================================================================
//================  GET STOCKS BY PRODUCT CODE  ======================================================================================================
//======================================================================================================================

    public function getStockByProductCode(int $productCode)
    {
        try {
            $stock = $this->stockManagementService->getStockByProductCode($productCode);

            return ApiResponse::success(
                'Stock quantity retrieved successfully',
                200,
                new StockResource($stock)

            );
        } catch (Exception $e) {
            return ApiResponse::error(
                "Failed to retrieve stock quantity by product code $productCode",
                400,
                $e->getMessage()
            );
        }
    }

//======================================================================================================================
//================  GET STOCKS OF ALL PRODUCTS  ======================================================================================================
//======================================================================================================================

    public function getStockOfAllProducts() {
        try{
            $stockOfAllProducts = $this->stockManagementService->getStockOfAllProducts();
            return ApiResponse::success(
                'Stock of All Products retrieved successfully',
                200,
                new StockResource($stockOfAllProducts)
            );
        }
        catch (Exception $e) {
            return ApiResponse::error(
                'Stock of All Products not found',
                400,
                new StockResource($stockOfAllProducts)
            );
                }
    }


//=======================================================================================================================
//===============  DEDUCT STOCK BY PRODUCT CODE  =============================================================================
//=======================================================================================================================

    public function deductStockByProductCode(StockUpdateRequest $request)
    {
        try{
            $movementDetails = [
                'email' => $request->header('email'),
                'source' => $request->header('source'),
                'movement_type' => $request->header('movement_type'),
            ];
            if(!$movementDetails['email'] || !$movementDetails['source'] || !$movementDetails['movement_type']) {
                throw new Exception("Header details missing");
            }
            $stock = $this->stockManagementService->deductStockByProductCode($movementDetails, $request->validated());

            return ApiResponse::success(
                'Stock deducted successfully',
                200,
                new StockResource($stock)
            );
        }
        catch (Exception $e) {
            return ApiResponse::error(
                'Stock deducted failed',
                400,
                $e->getMessage()
            );
        }


    }

//=======================================================================================================================
//===============  ADD STOCK BY PRODUCT CODE  =============================================================================
//=======================================================================================================================

    public function addStockByProductCode(StockCreateRequest $request)
    {
        try{
            $movementDetails = [
                'email' => $request->header('email'),
                'source' => $request->header('source'),
                'movement_type' => $request->header('movement_type'),
            ];
            if(!$movementDetails['email'] || !$movementDetails['source'] || !$movementDetails['movement_type']) {
                throw new Exception("Header details missing");
            }
            $stock = $this->stockManagementService->addStockByProductCode($movementDetails, $request->validated());

            return ApiResponse::success(
                'Stock Added successfully',
                200,
                new StockResource($stock)
            );
        }
        catch (Exception $e) {
            return ApiResponse::error(
                'Stock added failed',
                400,
                $e->getMessage()
            );
        }
    }

//=======================================================================================================================
//===============  AVAILABILITY OF STOCK : PRODUCT  =============================================================================
//=======================================================================================================================


//    public function availabilityOfProductStock(StockUpdateRequest $request)
//    {
//        try{
//            $available = $this->stockManagementService->availabilityOfProductStock($request->validated());
//
//            return ApiResponse::success(
//                'Available',
//                200,
//                $available
//            );
//        }catch (Exception $e) {
//            return ApiResponse::error(
//                'Unavailable',
//                400,
//                $e->getMessage()
//            );
//        }
//    }
}
