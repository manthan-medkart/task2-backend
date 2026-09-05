<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ApiResponse;
use App\Http\Resources\PurchaseOrderResource;
use App\Http\Services\PurchaseOrderService;
use Exception;

class PurchaseOrderController extends ApiController
{
    private PurchaseOrderService $purchaseOrderService;

    public function __construct(PurchaseOrderService $purchaseOrderService)
    {
        $this->purchaseOrderService = $purchaseOrderService;
    }


//======================================================================================================================
//================  GET ALL PURCHASE ORDERS  ======================================================================================================
//======================================================================================================================

    public function getAllPurchaseOrders()
    {
        try {
            $orders = $this->purchaseOrderService->getAllPurchaseOrders();
            return $this->resp('Purchase Orders found', 200 , new PurchaseOrderResource($orders));
        } catch (Exception $e) {
            return $this->resp('Failed to retrieve Purchase Orders', 500, $e->getMessage());
        }
    }

//======================================================================================================================
//================  CREATE PURCHASE ORDER  =============================================================================
//======================================================================================================================

    public function createPurchaseOrder(int $id)
    {
        try {
            $purchaseOrder = $this->purchaseOrderService->createPurchaseOrder($id);
            return $this->resp('PurchaseOrder created', 200 , new PurchaseOrderResource($purchaseOrder));
        } catch (Exception $e) {
            return $this->resp('Failed to create PurchaseOrder', 500, $e->getMessage());
        }
    }


//======================================================================================================================
//================  MARK PO SENT  ======================================================================================================
//======================================================================================================================

    public function markSent(int $id)
    {
        try {
            $order = $this->purchaseOrderService->markPoSent($id);
            return $this->resp('Purchase Order marked sent', 200 , new PurchaseOrderResource($order));
        } catch (Exception $e) {
            return $this->resp('Failed to mark Purchase Order', 400, $e->getMessage());
        }
    }


//======================================================================================================================
//================  MARK PO PROCURED  ======================================================================================================
//======================================================================================================================

    public function markProcured(int $id)
    {
        try {
            $order = $this->purchaseOrderService->markPoProcured($id);
            return $this->resp('Purchase Order marked procured', 200 , new PurchaseOrderResource($order));
        } catch (Exception $e) {
            return $this->resp('Failed to mark Purchase Order', 400, $e->getMessage());
        }
    }
}
