<?php

namespace App\Http\Services;

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psy\Exception\ParseErrorException;

class StockManagementService
{
    // GET /stock
    // GET /stock/{stock_id}
    // GET /stock/product/{product_code}

    // POST /stock/adjust

    private ProductService $productService;
    private StockMovementService $stockMovementService;

    public function __construct(ProductService $productService, StockMovementService $stockMovementService)
    {
        $this->productService = $productService;
        $this->stockMovementService = $stockMovementService;
    }

//=======================================================================================================================
//===============  GET STOCK OF ALL PRODUCTS  ================================================================================
//=======================================================================================================================

    public function getStockOfAllProducts(): Collection
    {
        return DB::transaction(function () {
            return Stock::all();
        });
    }

//=======================================================================================================================
//===============  GET STOCK BY PRODUCT CODE  ===========================================================================
//=======================================================================================================================

    public function getStockByProductCode(int $productCode): Stock
    {
        return DB::transaction(function () use ($productCode) {
            // Check if product of given product code exists.
            $this->productService->productExists($productCode);

            // Find Stock using product_code
            $stock = Stock::where('product_code', $productCode)->first();
            // If stock doesn't exists, throw exception.
            if (!$stock) {
                throw new ModelNotFoundException("No Stock for Product Code $productCode found");
            }
            return $stock;
        });
    }

//=======================================================================================================================
//===============  GET STOCK BY STOCK ID  ===============================================================================
//=======================================================================================================================

    public function getStockById(int $stockId): Stock
    {
        return DB::transaction(function () use ($stockId) {
            return Stock::find($stockId);
        });
    }

//=======================================================================================================================
//===============  ADJUST STOCK  ========================================================================================
//=======================================================================================================================

    public function adjustStock(array $data): Stock
    {
        return DB::transaction(function () use ($data) {

            // Inside this method only it will check if product with particular $product code exists or not
            // If product not exists, throw exception.
            // If exists it will return stock.
            $stock = $this->getStockByProductCode($data['product_code']);

            $updateQuantity = $data["quantity"];
            if ($updateQuantity < 0) {
                throw new \Exception("Stock can't be negative");
            }
            $stock->quantity = $updateQuantity;
            $stock->save();
            return $stock;
        });
    }

//=======================================================================================================================
//==============  DEDUCT STOCK BY PRODUCT CODE  ===========================================================================
//=======================================================================================================================

    public function deductStockByProductCode(array $movementDetails, array $data): Stock
    {
        return DB::transaction(function () use ($data, $movementDetails) {
            // Get Stock using product code
            $stock = $this->getStockByProductCode($data['product_code']);
            //Check that stock after deduction should not become negative
            if ($stock->quantity - $data['quantity'] < 0) {
                throw new \Exception("Stock can't be negative");
            }
            $stock->quantity -= $data['quantity'];
            $stock->save();

            // Update available stock in product model
            $this->productService->updatePartialProduct($data['product_code'], ['total_strip' => $stock->quantity]);

            // Create Stock Movement
            $stockMovement = $this->stockMovementService->createStockMovement([
                'product_code' => $data['product_code'],
                'movement_type' => $movementDetails['movement_type'],
                'quantity_change' => -$data['quantity'],
                'before_quantity' => $stock->quantity + $data['quantity'],
                'after_quantity' => $stock->quantity,
                'source' => $movementDetails['source'],
                'updated_by' => $movementDetails['email'],
            ]);
            return $stock;
        });
    }


//=======================================================================================================================
//===============  ADD STOCK BY PRODUCT CODE  =============================================================================
//=======================================================================================================================

    public function addStockByProductCode(array $movementDetails, array $data): Stock
    {
        return DB::transaction(function () use ($data, $movementDetails) {
            $stock = $this->getStockByProductCode($data['product_code']);
            $stock->quantity += $data['quantity'];
            $stock->save();
            $this->stockMovementService->createStockMovement([
                'product_code' => $data['product_code'],
                'movement_type' => $movementDetails['movement_type'],
                'quantity_change' => $data['quantity'],
                'before_quantity' => $stock->quantity - $data['quantity'],
                'after_quantity' => $stock->quantity,
                'source' => $movementDetails['source'],
                'updated_by' => $movementDetails['email'],
            ]);
            return $stock;

        });
        // Get stock using product code
    }


//=======================================================================================================================
//===============  AVAILABILITY OF PRODUCT  =============================================================================
//=======================================================================================================================

    public function availabilityOfProductStock(int $product_code, int $quantity) : bool
    {
            //First fetch current stock.
            $stock = $this->getStockByProductCode($product_code);

            return $stock->quantity >= $quantity;
    }

//=======================================================================================================================
//================  CREATE STOCK OF NEW PRODUCT  ===========================================================================
//=======================================================================================================================

    public function createStockOfNewProduct(array $data) : Stock{
        return DB::transaction(function () use ($data){
            $productCode = Stock::where('product_code', $data['product_code'])->first();
            if($productCode){throw new ModelNotFoundException("Already Stock exists for Product Code $data[product_code]");}
            return Stock::create([
                'product_code' => $data['product_code'],
                'quantity' => $data['quantity'],
            ]);
        });
    }

}
