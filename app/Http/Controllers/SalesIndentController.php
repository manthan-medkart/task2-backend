<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\ApiResponse;
use App\Http\Requests\UpdateSalesIndentQuantityRequest;
use App\Http\Requests\CreatePurchaseIndentRequest;
use App\Http\Resources\SalesIndentResource;
use App\Http\Resources\PurchaseIndentResource;
use App\Http\Services\SalesIndentService;
use Exception;

class SalesIndentController extends Controller
{
    private SalesIndentService $salesIndentService;

    public function __construct(SalesIndentService $salesIndentService)
    {
        $this->salesIndentService = $salesIndentService;
    }


//======================================================================================================================
//================  GET ALL SALES INDENTS  ======================================================================================================
//======================================================================================================================

    public function getAllSalesIndents()
    {
        try {
            $indents = $this->salesIndentService->getAllSalesIndents();

            return ApiResponse::success(
                'Sales Indents retrieved successfully',
                200,
                SalesIndentResource::collection($indents)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve Sales Indents',
                400,
                $e->getMessage()
            );
        }
    }
}
