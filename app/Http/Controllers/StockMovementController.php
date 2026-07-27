<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StockMovementRequest;
use App\Http\Resources\StockMovementResource;
use App\Http\Services\StockMovementService;
use Exception;

class StockMovementController extends Controller
{
    private StockMovementService $stockMovementService;
    public function __construct(StockMovementService $stockMovementService){
        $this->stockMovementService = $stockMovementService;
    }

//======================================================================================================================
//===============  GET ALL STOCK MOVEMENTS  ============================================================================
//======================================================================================================================

    public function getAllStockMovements(){
        try{
            $stockMovements = $this->stockMovementService->getAllStockMovements();
            return ApiResponse::success(
                'Successfully retrieved stock movements',
                200,
                new StockMovementResource($stockMovements)
            );
        }
        catch (Exception $exception){
            return ApiResponse::error(
                'Retrieving stock movements get failed',
                400,
                $exception->getMessage()
            );
        }

    }

//======================================================================================================================
//===============  CREATE STOCK MOVEMENT  ============================================================================
//======================================================================================================================

    public function createStockMovement(StockMovementRequest $request){
        try{
            $stockMovement = $this->stockMovementService->createStockMovement($request->validated());
            return ApiResponse::success(
                'Successfully added stock movement',
                200,
                new StockMovementResource($stockMovement)
            );
        }catch (Exception $exception){
            return ApiResponse::error(
                'Failed to add stock movement',
                400,
                $exception->getMessage()
            );
        }
    }




}
