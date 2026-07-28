<?php

use App\Http\Controllers\OrderWorkflowController;
use App\Http\Controllers\SalesOrderController;


// ==========================================
// Sales Order Routes (from ecommerce)
// ==========================================

Route::post('sales-orders/create', [OrderWorkflowController::class, 'createSalesOrder']);
Route::get('sales-orders/can-order-place/{id}', [SalesOrderController::class, 'canOrderPlace']);
Route::get('sales-orders/{salesOrderId}/details', [SalesOrderController::class, 'getSalesOrderDetails'])->whereNumber('salesOrderId');
Route::get('sales-orders', [SalesOrderController::class, 'getAllSalesOrders']);
Route::post('sales-orders/{id}/accept', [SalesOrderController::class, 'acceptSalesOrder'])->whereNumber('id');
Route::post('sales-orders/{id}/cancel', [SalesOrderController::class, 'cancelSalesOrder'])->whereNumber('id');

//Route::get('sales-order/{salesOrderId}/details', [OrderWorkflowController::class, 'getSalesOrderDetails'])->whereNumber('salesOrderId');
