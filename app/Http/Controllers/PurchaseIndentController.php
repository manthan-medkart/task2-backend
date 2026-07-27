<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePurchaseIndentRequest;
use Illuminate\Http\Request;

use App\Helpers\ApiResponse;
use App\Http\Resources\PurchaseIndentResource;
use App\Http\Resources\PurchaseOrderResource;
use App\Http\Services\PurchaseIndentService;
use Exception;

class PurchaseIndentController extends Controller
{
    private PurchaseIndentService $purchaseIndentService;

    public function __construct(PurchaseIndentService $purchaseIndentService)
    {
        $this->purchaseIndentService = $purchaseIndentService;
    }


//======================================================================================================================
//================  GET ALL PURCHASE INDENTS  ======================================================================================================
//======================================================================================================================

    public function getAllPurchaseIndents()
    {
        try {
            $indents = $this->purchaseIndentService->getAllPurchaseIndents();

            return ApiResponse::success(
                'Purchase Indents retrieved successfully',
                200,
                new PurchaseIndentResource($indents)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to retrieve Purchase Indents',
                400,
                $e->getMessage()
            );
        }
    }

//======================================================================================================================
//================  CREATE PURCHASE INDENT  ======================================================================================================
//======================================================================================================================

    public function createPurchaseIndent(CreatePurchaseIndentRequest $request)
    {
        try {
            $purchaseIndent = $this->purchaseIndentService->createPurchaseIndent(
                $request->validated()['sales_indent_ids']
            );

            return ApiResponse::success(
                'Purchase Indent created successfully',
                200,
                new PurchaseIndentResource($purchaseIndent)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to create Purchase Indent',
                400,
                $e->getMessage()
            );
        }
    }


    /**
     * Download PDF for a purchase indent.
     * Currently returns the data — add your PDF generation logic in the service.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloadPdf(int $id)
    {
        try {
            $purchaseIndent = $this->purchaseIndentService->downloadPdf($id);

            // TODO: When you add PDF generation, return a file download response instead.
            // For now, return the data that would be used for the PDF.
            return ApiResponse::success(
                'Purchase Indent data for PDF (PDF generation not yet implemented)',
                200,
                new PurchaseIndentResource($purchaseIndent)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to generate PDF',
                400,
                $e->getMessage()
            );
        }
    }
}
