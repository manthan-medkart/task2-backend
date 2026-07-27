<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ApiResponse;
use App\Http\Requests\SalesOrderCreateRequest;
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
     * @param SalesOrderCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSalesOrder(SalesOrderCreateRequest $request)
    {
        try {
            $order = $this->orderWorkflowService->createSalesOrder($request->validated());

            return ApiResponse::success(
                'Sales Order created successfully',
                200,
                null
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to create Sales Order',
                400,
                $e->getMessage()
            );
        }
    }
}
