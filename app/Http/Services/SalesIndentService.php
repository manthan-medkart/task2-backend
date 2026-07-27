<?php

namespace App\Http\Services;

use App\Models\SalesIndent;
use App\Models\PurchaseIndent;
use App\Models\PurchaseIndentItem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesIndentService
{

//======================================================================================================================
//===============  GET ALL SALES INDENTS  =======================================================================================================
//======================================================================================================================

    public function getAllSalesIndents(): Collection
    {
        return SalesIndent::with('product', 'salesOrder', 'purchaseIndentItem')
            ->orderBy('created_at', 'desc')
            ->get();
    }


//======================================================================================================================
//===============  CREATE SALES INDENT  =======================================================================================================
//======================================================================================================================

    public function createSalesIndent(array $data) : SalesIndent{
        return DB::transaction(function () use ($data) {
            return SalesIndent::create([
                'sales_order_id' => $data['sales_order_id'],
                'product_code' => $data['product_code'],
                'product_name' => $data['product_name'],
                'quantity' => $data['quantity'],
                'status' => $data['status'],
            ]);
        });
    }
}
