<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesOrderCreateRequest;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

use App\Helpers\ApiResponse;
use App\Http\Resources\SalesOrderResource;
use App\Http\Services\SalesOrderService;
use Exception;
use Spatie\FlareClient\Api;

class SalesOrderController extends ApiController
{
    private SalesOrderService $salesOrderService;

    public function __construct(SalesOrderService $salesOrderService)
    {
        $this->salesOrderService = $salesOrderService;
    }


//======================================================================================================================
//===============  CREATE SALES ORDER MANUALLY  ========================================================================
//======================================================================================================================

//    public function createSalesOrderManually(SalesOrderCreateRequest $request){
//        try{
//            $this->salesOrderService->createSalesOrderManually($request->validated());
//        }
//    }


//======================================================================================================================
//===============  GET DETAILS OF SALES ORDER  =========================================================================
//======================================================================================================================

    public function getSalesOrderDetails(int $salesOrderId){
        try{
            $salesOrder = $this->salesOrderService->getSalesOrderDetails($salesOrderId);
            return $this->resp('Successfully get sales order details', 200 , new SalesOrderResource($salesOrder));
        }
        catch (Exception $e){
            return $this->resp('Failed get sales order details', 400 , $e->getMessage());
        }
    }

//======================================================================================================================
//===============  GET All Sales Orders  =========================================================================
//======================================================================================================================

    public function getAllSalesOrders(Request $request)
    {
        try {
            $orders = $this->salesOrderService->getAllSalesOrders();
            return $this->resp('Successfully get sales orders', 200 , SalesOrderResource::collection($orders));
        } catch (Exception $e) {
            return $this->resp('Failed get sales orders', 400 , $e->getMessage());
        }
    }

//======================================================================================================================
//================  ACCEPT SALES ORDER  ======================================================================================================
//======================================================================================================================

    public function acceptSalesOrder(int $id)
    {
        try {
            $order = $this->salesOrderService->acceptSalesOrder($id);

            $message = $order->status === 'ACCEPTED'
                ? 'Order accepted successfully. Stock is available.'
                : 'Order is checking availability. Sales indent created for unavailable items.';
            return $this->resp($message, 200 , new SalesOrderResource($order));
        } catch (Exception $e) {
            return $this->resp('Failed get sales orders', 400 , $e->getMessage());
        }
    }

//======================================================================================================================
//===============  CANCEL SALES ORDER  =========================================================================
//======================================================================================================================

    public function cancelSalesOrder(int $id)
    {
        try {
            $order = $this->salesOrderService->cancelSalesOrder($id);
            return $this->resp('Order cancelled successfully', 200 , new SalesOrderResource($order));
        } catch (Exception $e) {
            return $this->resp('Failed to cancel sales orders', 400 , $e->getMessage());
        }
    }

//======================================================================================================================
//===============  CAN ORDER GET DELIVERED  ============================================================================
//======================================================================================================================

    public function canOrderPlace(int $id){
        try{
            $this->salesOrderService->canOrderPlace($id);
            return $this->resp('Order places successfully', 200 , null);
        }catch (Exception $e){
            return $this->resp('Failed to place order', 400 , $e->getMessage());
        }

    }

}
