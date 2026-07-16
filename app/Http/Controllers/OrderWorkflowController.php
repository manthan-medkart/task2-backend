<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ApiResponse;
use App\Http\Requests\CreateSalesOrderRequest;
use App\Http\Requests\ProcessDeliveryRequest;
use App\Http\Resources\SalesOrderResource;
use App\Http\Services\OrderWorkflowService;
use Exception;

class OrderWorkflowController extends Controller
{
    private OrderWorkflowService $orderWorkflowService;

    public function __construct(OrderWorkflowService $orderWorkflowService)
    {
        $this->orderWorkflowService = $orderWorkflowService;
    }

    /**
     * Create WMS Sales Order from E-Commerce Order.
     *
     * @param CreateSalesOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSalesOrder(CreateSalesOrderRequest $request)
    {
        try {
            $order = $this->orderWorkflowService->createSalesOrder($request->validated());

            return ApiResponse::success(
                'Sales Order created successfully',
                200,
                new SalesOrderResource($order)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to create Sales Order',
                400,
                $e->getMessage()
            );
        }
    }

    /**
     * Generate Sales Invoice for an order.
     *
     * @param int $salesOrderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateInvoice(int $salesOrderId)
    {
        try {
            $order = $this->orderWorkflowService->generateInvoice($salesOrderId);

            return ApiResponse::success(
                'Sales Invoice generated successfully',
                200,
                new SalesOrderResource($order)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to generate Sales Invoice',
                400,
                $e->getMessage()
            );
        }
    }

    /**
     * Process Delivery for an invoiced order (deducts stock).
     *
     * @param int $salesOrderId
     * @param ProcessDeliveryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processDelivery(int $salesOrderId, ProcessDeliveryRequest $request)
    {
        try {
            $order = $this->orderWorkflowService->processDelivery($salesOrderId, $request->validated());

            return ApiResponse::success(
                'Delivery processed and stock updated successfully',
                200,
                new SalesOrderResource($order)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to process Delivery',
                400,
                $e->getMessage()
            );
        }
    }

    /**
     * Get detailed status of a Sales Order.
     *
     * @param int $salesOrderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSalesOrderDetails(int $salesOrderId)
    {
        try {
            $order = $this->orderWorkflowService->getSalesOrderDetails($salesOrderId);

            return ApiResponse::success(
                'Sales Order details retrieved successfully',
                200,
                new SalesOrderResource($order)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve Sales Order details',
                404,
                $e->getMessage()
            );
        }
    }
}
