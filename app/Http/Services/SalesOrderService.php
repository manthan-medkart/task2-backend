<?php

namespace App\Http\Services;

use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesIndent;
use App\Http\Services\StockService;
use http\Exception\RuntimeException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Throwable;

class SalesOrderService
{
    private SalesIndentService $salesIndentService;
    private StockManagementService $stockManagementService;
    private OrderWorkflowService $orderWorkflowService;

    public function __construct(SalesIndentService $salesIndentService, StockManagementService $stockManagementService, OrderWorkflowService $orderWorkflowService)
    {
        $this->salesIndentService = $salesIndentService;
        $this->stockManagementService = $stockManagementService;
        $this->orderWorkflowService = $orderWorkflowService;
    }

    /**
     * Get all sales orders with their items.
     *
     * @return Collection
     */
    public function getAllSalesOrders(): Collection
    {

        $salesOrder = SalesOrder::all()->sortByDesc('created_at');
        $salesOrder->load('items.product');
        return $salesOrder;
    }



    public function acceptSalesOrder(int $salesOrderId): SalesOrder
    {
        try{

            return DB::transaction(function () use ($salesOrderId) {
                $salesOrder = SalesOrder::find($salesOrderId);
                if (!$salesOrder) {
                    throw new ModelNotFoundException("Sales Order ID {$salesOrderId} not found.");
                }

                if ($salesOrder->status !== 'PENDING') {
                    throw new Exception("Only pending orders can be accepted. Current status: {$salesOrder->status}");
                }
                $salesOrder->load('items.product');

                $allItemsAvailable = true;
                foreach ($salesOrder->items as $item) {
                    $isAvailable = $this->stockManagementService->availabilityOfProductStock($item->product_code, $item->quantity);
                    if (!$isAvailable) {
                        $allItemsAvailable = false;

                        //Sales Indent Generated
                        $data = [
                            'sales_order_id' => $salesOrderId,
                            'product_code' => $item->product_code,
                            'product_name' => $item->product->name,
                            'quantity' => $item->quantity,
                            'status' => 'PENDING'
                        ];
                        $this->salesIndentService->createSalesIndent($data);

                    }

                }
                // If all item would be available, change sale order status as ACCEPTED
                // and mark ecommerce order status as DELIVERED.
                if($allItemsAvailable) {
                    $salesOrder->status = 'ACCEPTED';
                    $this->orderWorkflowService->notifyEcommerce($salesOrder->ecommerce_order_id, 'DELIVERED');
                }
                else{
                    $salesOrder->status = 'CHECKING AVAILABILITY';
                }
                $salesOrder->save();
                $salesOrder->refresh();
                return $salesOrder;
            });
        }catch (Exception $exception){
            throw new Exception($exception->getMessage());
        }
    }


    public function cancelSalesOrder(int $salesOrderId): SalesOrder
    {
        try{

            $salesOrder = DB::transaction(function () use ($salesOrderId) {
                // first get the saler order model.
                $salesOrder = SalesOrder::find($salesOrderId);
                if (!$salesOrder) {throw new ModelNotFoundException("Sales Order ID {$salesOrderId} not found.");}
                $salesOrder->load('items');

                // if status = PENDING -> then only change status = CANCELLED.
                if($salesOrder->status === 'PENDING') {
                    // Restock of all items included in that order.
                    foreach($salesOrder->items as $item) {
                        $movementDetails = [
                            'email' => 'admin@gmail.com',
                            'source' => 'WMS',
                            'movement_type' => 'SALES_ORDER_CANCEL',
                        ];
                        $stock = [
                            'product_code' => $item->product_code,
                            'quantity' => $item->quantity,
                        ];

                        $this->stockManagementService->addStockByProductCode($movementDetails, $stock);

                    }

                    // Change status to CANCEL at Ecommerce side.
                    $this->orderWorkflowService->notifyEcommerce($salesOrder->ecommerce_order_id, 'CANCELLED');
                    // Change status = CANCELLED.
                    $salesOrder->status = 'CANCELLED';
                    $salesOrder->save();
                    $salesOrder->refresh();
                    return $salesOrder;
                }
                else throw new Exception('Cannot Cancel Sales Order.');
            });
            return $salesOrder;
        }
        catch (Exception $e) {
            Log::error($e->getMessage());
            throw new Exception($e->getMessage());
        }
    }


//======================================================================================================================
//================  CREATE SALES ORDER MANUALLY  ======================================================================================================
//======================================================================================================================
//    public function createSalesOrderManually(array $data)
//    {
//        try{
//            // Check if 'ecom_order_id' exists on ecommerce side.
////            $response = Http::get("http://localhost:8080/api/orders/{orderId}/exists");
////            $body = json_decode($response->body());
////            dd($body);
////            if (!$body) {throw new Exception("Failed to create sales order.");}
//
//            DB::transaction(function () use ($data) {
//
//            })
//
//        }catch (Exception $e){
//            Log::error("Failed to create sales order.");
//        }
//
//
//    }


//======================================================================================================================
//================  GET DETAILS OF SALES ORDER  ======================================================================================================
//======================================================================================================================

    public function getSalesOrderDetails(int $salesOrderId) : SalesOrder
    {
        try{
            $salesOrder = SalesOrder::find($salesOrderId);
            $salesOrder->load('items.product');
            return $salesOrder;
        }
        catch (Exception $e) {
            Log::error("Failed to get sales order details.");
            return throw new RuntimeException("Failed to get sales order details.");
        }
    }

//======================================================================================================================
//================  CAN ORDER GET DELIVERED  ======================================================================================================
//======================================================================================================================

    public function canOrderPlace(int $ecommerceOrderId) {
        try{
            $salesOrder = SalesOrder::where('ecommerce_order_id', $ecommerceOrderId)->first();
            if(!$salesOrder){throw new ModelNotFoundException("Sales Order  with ecommerce_order_id {$ecommerceOrderId} not found.");}
            if($salesOrder->status !== 'CHECKING AVAILABILITY') {throw new Exception("Only CHECKING AVAILABILITY orders can be check.");}

            $salesOrder->load('items');
            foreach($salesOrder->items as $item){
                $isAvailable = $this->stockManagementService->availabilityOfProductStock($item->product_code, $item->quantity);
                if(!$isAvailable) {throw new Exception("Only available stock can be check.");}
            }

            $this->orderWorkflowService->notifyEcommerce($salesOrder->ecommerce_order_id, 'DELIVERED');
        }catch (Exception $exception){
            throw $exception;
        }
    }
}
