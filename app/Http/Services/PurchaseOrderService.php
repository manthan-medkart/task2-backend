<?php

namespace App\Http\Services;

use App\Models\PurchaseIndent;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PurchaseOrderService
{
    private StockManagementService $stockManagementService;
    private SalesOrderService $salesOrderService;

    public function __construct(StockManagementService $stockManagementService, SalesOrderService $salesOrderService)
    {
        $this->stockManagementService = $stockManagementService;
        $this->salesOrderService = $salesOrderService;

    }

    /**
     * Get all purchase orders with their items.
     *
     * @return Collection
     */
    public function getAllPurchaseOrders(): Collection
    {
        return PurchaseOrder::with('items.product', 'purchaseIndent')
            ->orderBy('created_at', 'desc')
            ->get();
    }

//======================================================================================================================
//================  CREATE PURCHASE ORDER  =============================================================================
//======================================================================================================================

    public function createPurchaseOrder(int $purchaseIndentId): PurchaseOrder
    {
        return DB::transaction(function () use ($purchaseIndentId) {
            $purchaseIndent = PurchaseIndent::find($purchaseIndentId);
            if (!$purchaseIndent) {
                throw new ModelNotFoundException("Purchase Indent ID {$purchaseIndentId} not found.");
            }

            if ($purchaseIndent->status !== 'PENDING') {
                throw new Exception(
                    "Purchase Order already created for this indent. Current status: {$purchaseIndent->status}"
                );
            }
            $purchaseIndent->load('items.product', 'items.salesIndent.salesOrder');

            // Generate unique PO number
            $poNumber = 'PO-' . time() . rand(10, 99);

            // Create purchase order
            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $poNumber,
                'purchase_indent_id' => $purchaseIndent->id,
                'status' => 'PENDING'
            ]);

            // Copy items from purchase indent to purchase order
            foreach ($purchaseIndent->items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_code' => $item->product_code,
                    'quantity' => $item->quantity,
                    'ecommerce_order_id' => $item->salesIndent->salesOrder->ecommerce_order_id,
                ]);
            }

            // Update purchase indent status
            $purchaseIndent->status = 'PO CREATED';
            $purchaseIndent->save();

            return $purchaseOrder->load('items.product', 'purchaseIndent');
        });
    }

    public function markPoSent(int $purchaseOrderId): PurchaseOrder
    {
        $purchaseOrder = PurchaseOrder::find($purchaseOrderId);
        if (!$purchaseOrder) {
            throw new ModelNotFoundException("Purchase Order ID {$purchaseOrderId} not found.");
        }

        if ($purchaseOrder->status !== 'PENDING') {
            throw new Exception(
                "Can only mark as sent when status is PENDING. Current status: {$purchaseOrder->status}"
            );
        }

        $purchaseOrder->status = 'PO SENT';
        $purchaseOrder->save();

        return $purchaseOrder->load('items.product', 'purchaseIndent');
    }

    public function markPoProcured(int $purchaseOrderId): PurchaseOrder
    {
        try{

            $purchaseOrder = DB::transaction(function () use ($purchaseOrderId) {
                $purchaseOrder = PurchaseOrder::find($purchaseOrderId);

                if (!$purchaseOrder) {
                    throw new ModelNotFoundException("Purchase Order ID {$purchaseOrderId} not found.");
                }

                if ($purchaseOrder->status !== 'PO SENT') {
                    throw new Exception(
                        "Can only mark as procured when status is po_sent. Current status: {$purchaseOrder->status}"
                    );
                }

                $purchaseOrder->load('items.product');

                // 1. Add stock for each item in the purchase order
                foreach ($purchaseOrder->items as $item) {
                    $movementDetails = [
                        'email' => 'procurement@gmail.com',
                        'source' => 'WMS',
                        'movement_type' => 'PROCUREMENT',
                    ];
                    $stockDetails = [
                        'product_code' => $item->product_code,
                        'quantity' => $item->quantity,
                    ];
                    $this->stockManagementService->addStockByProductCode($movementDetails, $stockDetails);
                }

                // 2. Update purchase order status
                $purchaseOrder->status = 'PO PROCURED';
                $purchaseOrder->save();
                $purchaseOrder->refresh();
                return $purchaseOrder;
            });
            foreach($purchaseOrder->items as $item){
                $this->salesOrderService->canOrderPlace($item->ecommerce_order_id);
            }
            return $purchaseOrder;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    private function notifyEcommerce(string $ecommerceOrderId, string $status): void
    {
        try {
            $ecommerceUrl = env('ECOMMERCE_API_URL', 'http://localhost:8080');
            $response = Http::post(
                "{$ecommerceUrl}/api/orders/update-status/{$ecommerceOrderId}?status={$status}"
            );

            if (!$response->successful()) {
                Log::error(
                    "Failed to update status in E-Commerce. Status code: "
                    . $response->status() . " Response: " . $response->body()
                );
            }
        } catch (\Exception $e) {
            Log::error("Failed to notify E-Commerce system: " . $e->getMessage());
        }
    }
}
