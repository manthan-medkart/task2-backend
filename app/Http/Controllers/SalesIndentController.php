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

class SalesIndentController extends ApiController
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

            return $this->resp('Sales Indents found', 200 , SalesIndentResource::collection($indents));
        } catch (Exception $e) {
            return $this->resp('Failed to get all Sales Indents', 400, $e->getMessage());
        }
    }
}
