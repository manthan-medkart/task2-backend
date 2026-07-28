<?php


use App\Http\Controllers\PurchaseOrderController;

// ==========================================
// Purchase Order Routes
// ==========================================

Route::get('purchase-orders', [PurchaseOrderController::class, 'getAllPurchaseOrders']);
Route::post('purchase-orders/{id}/mark-sent', [PurchaseOrderController::class, 'markSent'])->whereNumber('id');
Route::post('purchase-orders/{id}/mark-procured', [PurchaseOrderController::class, 'markProcured'])->whereNumber('id');

