<?php

use App\Http\Controllers\PurchaseIndentController;
use App\Http\Controllers\SalesIndentController;


// ==========================================
// Sales Indent Routes
// ==========================================

Route::get('sales-indents', [SalesIndentController::class, 'getAllSalesIndents']);
Route::post('sales-indents/purchase-indent', [PurchaseIndentController::class, 'createPurchaseIndent']);

