<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ApiResponse;
use App\Http\Resources\PurchaseOrderResource;
use App\Http\Services\PurchaseOrderService;
use Exception;

class PurchaseOrderController extends Controller
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

            return ApiResponse::success(
                'Purchase Orders retrieved successfully',
                200,
                new PurchaseOrderResource($orders)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve Purchase Orders',
                400,
                $e->getMessage()
            );
        }
    }

//======================================================================================================================
//================  CREATE PURCHASE ORDER  =============================================================================
//======================================================================================================================

    public function createPurchaseOrder(int $id)
    {
        try {
            $purchaseOrder = $this->purchaseOrderService->createPurchaseOrder($id);

            return ApiResponse::success(
                'Purchase Order created successfully',
                200,
                new PurchaseOrderResource($purchaseOrder)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to create Purchase Order',
                400,
                $e->getMessage()
            );
        }
    }


//======================================================================================================================
//================  MARK PO SENT  ======================================================================================================
//======================================================================================================================

    public function markSent(int $id)
    {
        try {
            $order = $this->purchaseOrderService->markPoSent($id);

            return ApiResponse::success(
                'Purchase Order marked as sent to vendor',
                200,
                new PurchaseOrderResource($order)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to mark Purchase Order as sent',
                400,
                $e->getMessage()
            );
        }
    }


//======================================================================================================================
//================  MARK PO PROCURED  ======================================================================================================
//======================================================================================================================

    public function markProcured(int $id)
    {
        try {
            $order = $this->purchaseOrderService->markPoProcured($id);

            return ApiResponse::success(
                'Purchase Order procured. Stock updated and order delivered.',
                200,
                new PurchaseOrderResource($order)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to mark Purchase Order as procured',
                400,
                $e->getMessage()
            );
        }
    }
}
