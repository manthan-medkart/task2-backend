<?php

use App\Http\Controllers\PurchaseIndentController;
use App\Http\Controllers\PurchaseOrderController;

// ==========================================
// Purchase Indent Routes
// ==========================================

Route::get('purchase-indents', [PurchaseIndentController::class, 'getAllPurchaseIndents']);
Route::get('purchase-indents/{id}/pdf', [PurchaseIndentController::class, 'downloadPdf'])->whereNumber('id');
Route::post('sales-indents/purchase-indent', [PurchaseIndentController::class, 'createPurchaseIndent']);
Route::post('purchase-indents/{id}/purchase-order', [PurchaseOrderController::class, 'createPurchaseOrder'])->whereNumber('id');
