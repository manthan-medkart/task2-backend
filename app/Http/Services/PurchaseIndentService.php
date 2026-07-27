<?php

namespace App\Http\Services;

use App\Models\PurchaseIndent;
use App\Models\PurchaseIndentItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SalesIndent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class PurchaseIndentService
{

    public function getAllPurchaseIndents(): Collection
    {
        return PurchaseIndent::with('items.product', 'items.salesIndent', 'purchaseOrder')
            ->orderBy('created_at', 'desc')
            ->get();
    }


//======================================================================================================================
//===============  CREATE PURCHASE INDENT  =============================================================================
//======================================================================================================================

    public function createPurchaseIndent(array $salesIndentIds): PurchaseIndent
    {
        try {

            return DB::transaction(function () use ($salesIndentIds) {
                // Validate all sales indent IDs exist and are pending
                $salesIndents = [];
                foreach ($salesIndentIds as $salesIndentId) {
                    $salesIndent = SalesIndent::find($salesIndentId);
                    if(!$salesIndent->exists()){throw new Exception('SalesIndent not found for id : ' . $salesIndentId);}
                    if($salesIndent->status !== 'PENDING') {throw new Exception('Only Pending SalesIndent created. SalesIndent status : ' . $salesIndent->status);}
                    $salesIndents[] = $salesIndent->load('product');
                }

                // Generate unique indent number
                $indentNumber = 'PI-' . time() . rand(10, 99);

                // Create purchase indent
                $purchaseIndent = PurchaseIndent::create([
                    'indent_number' => $indentNumber,
                    'status' => 'PENDING',
                ]);
                $purchaseIndent->refresh();
                // Create purchase indent items from each sales indent
                foreach ($salesIndents as $salesIndent) {
                    PurchaseIndentItem::create([
                        'purchase_indent_id' => $purchaseIndent->id,
                        'sales_indent_id' => $salesIndent->id,
                        'product_code' => $salesIndent->product_code,
                        'quantity' => $salesIndent->quantity,
                    ]);

                    // Update sales indent status
                    $salesIndent->status = 'PI CREATED';
                    $salesIndent->save();
                }
                return $purchaseIndent->load('items.product');
            });
        }catch (Exception $e){
            throw $e;
        }
    }

    public function getPurchaseIndentById(int $purchaseIndentId): PurchaseIndent
    {
        $purchaseIndent = PurchaseIndent::find($purchaseIndentId);

        if (!$purchaseIndent) {
            throw new ModelNotFoundException("Purchase Indent ID {$purchaseIndentId} not found.");
        }
        $purchaseIndent->load('items.product', 'items.salesIndent');
        $purchaseIndent->refresh();
        return $purchaseIndent;
    }

//    /**
//     * Placeholder for PDF download.
//     * Returns the purchase indent data that would be needed for PDF generation.
//     * TODO: Add your PDF generation logic here (e.g., using barryvdh/laravel-dompdf).
//     *
//     * @param int $purchaseIndentId
//     * @return PurchaseIndent
//     */
//    public function downloadPdf(int $purchaseIndentId): PurchaseIndent
//    {
//        $purchaseIndent = $this->getPurchaseIndentById($purchaseIndentId);
//
//        // =====================================================
//        // TODO: Add your PDF generation logic here.
//        //
//        // Example using barryvdh/laravel-dompdf:
//        //
//        // $pdf = PDF::loadView('purchase-indent-pdf', [
//        //     'purchaseIndent' => $purchaseIndent
//        // ]);
//        // return $pdf->download("PI-{$purchaseIndent->indent_number}.pdf");
//        //
//        // =====================================================
//
//        return $purchaseIndent;
//    }

}
