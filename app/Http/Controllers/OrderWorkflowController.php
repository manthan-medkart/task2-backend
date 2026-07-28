<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ApiResponse;
use App\Http\Requests\SalesOrderCreateRequest;
use App\Http\Resources\SalesOrderResource;
use App\Http\Services\OrderWorkflowService;
use Exception;

class OrderWorkflowController extends ApiController
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
            return $this->resp('Sales Order created', 201, $order);
        } catch (Exception $e) {
            return $this->resp('Sales Order not created', 500, 'Internal Server Error');
        }
    }
}
