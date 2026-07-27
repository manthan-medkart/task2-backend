<?php

namespace App\Http\Services;

use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockMovementService
{
    // GET api/stock-movement
    // GET api/stock-movement/productCode/{productCode}
    // GET api/stock-movement/{id}    -------------------------------->     PENDING

    // POST api/stock-movement/create

//======================================================================================================================
//================  DEPENDENCIES AND DEPENDENCY INJECTION  =============================================================
//======================================================================================================================

    private ProductService $productService;
    public function __construct(ProductService $productService){
        $this->productService = $productService;
    }

//======================================================================================================================
//================  GET ALL RECENT STOCK MOVEMENTS  ====================================================================
//======================================================================================================================

    public function getAllStockMovements() {
        try{
            return DB::transaction(function () {
                return StockMovement::all()->sortByDesc('created_at');
            });
        }
        catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

//======================================================================================================================
//================  GET STOCK MOVEMENT OF PRODUCT  ====================================================================
//======================================================================================================================

    public function getStockMovementByProductCode($productCode) {
        try{
            return DB::transaction(function () use ($productCode) {
                $this->productService->productExists($productCode);
                $stockMovements = StockMovement::where('product_code', $productCode)->get();
                if($stockMovements->isEmpty()) {
                    throw new \Exception("No stock movements found for $productCode");
                }
                return $stockMovements;
            });
        }
        catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

//======================================================================================================================
//================  CREATE STOCK MOVEMENT  ====================================================================
//======================================================================================================================

    public function createStockMovement(array $data) {
            $this->productService->productExists($data['product_code']);
            return DB::transaction(function () use ($data) {
                $stockMovement = StockMovement::create([
                    'product_code' => $data['product_code'],
                    'movement_type' => $data['movement_type'],
                    'quantity_change' => $data['quantity_change'],
                    'before_quantity' => $data['before_quantity'],
                    'after_quantity' => $data['after_quantity'],
                    'source' => $data['source'],
                    'updated_by' => $data['updated_by'],
                ]);
                $stockMovement->refresh();
                return $stockMovement;
            });
    }


}
