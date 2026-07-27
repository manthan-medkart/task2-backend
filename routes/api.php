<?php

use App\Http\Controllers\StockMovementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OrderWorkflowController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SalesIndentController;
use App\Http\Controllers\PurchaseIndentController;
use App\Http\Controllers\PurchaseOrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')
    ->get('/user', function (Request $request) {
    return $request->user();
});


// ==========================================
// Product Routes
// ==========================================
Route::post('product/create', [ProductController::class, 'createProduct']);
Route::patch('product/update/{productCode}', [ProductController::class, 'updatePartialProduct'])->whereNumber('productCode');
Route::put('product/update/{productCode}', [ProductController::class, 'updateProduct'])->whereNumber('productCode');
Route::get('product', [ProductController::class, 'getAllProducts']);
Route::get('product/details/{productCode}', [ProductController::class, 'getProduct'])->whereNumber('productCode');
Route::post('product/publish/',[ProductController::class, 'publishProduct']);

// ==========================================
// Stock Routes (WMS = single source of truth)
// ==========================================
Route::post('stock/product/create', [StockController::class, 'createStockOfNewProduct']);
Route::post('stock/adjust', [StockController::class, 'adjustStock']);
Route::get('stock', [StockController::class, 'getStockOfAllProducts']);
Route::get('stock/product/{productCode}', [StockController::class, 'getStockByProductCode'])->whereNumber('productCode');
Route::patch('stock/product/deduct', [StockController::class, 'deductStockByProductCode']);
Route::patch('stock/product/add', [StockController::class, 'addStockByProductCode']);
Route::get('stock/product/{productCode}/available', [StockController::class, 'availabilityOfProductStock'])->whereNumber('productCode');
//Route::post('product/{productCode}/stock/update', [StockController::class, 'updateStock'])->whereNumber('productCode');
//Route::get('product/{productCode}/stock/history', [StockController::class, 'getStockHistory'])->whereNumber('productCode');
//Route::get('stock/{productCode}', [StockController::class, 'getStockQuantity'])->whereNumber('productCode');
//Route::post('stock/{productCode}/deduct', [StockController::class, 'deductStock'])->whereNumber('productCode');
//Route::get('stock-logs', [StockController::class, 'getAllStockLogs']);


// ==========================================
// Stock Movements
// ==========================================
Route::get('stock-movement', [StockMovementController::class, 'getAllStockMovements']);
Route::post('stock-movement/create', [StockMovementController::class, 'createStockMovement']);



// ==========================================
// Sales Order Routes (from ecommerce)
// ==========================================
Route::post('sales-orders/create', [OrderWorkflowController::class, 'createSalesOrder']);
Route::get('sales-orders/can-order-place/{id}', [SalesOrderController::class, 'canOrderPlace']);
//Route::get('sales-order/{salesOrderId}/details', [OrderWorkflowController::class, 'getSalesOrderDetails'])->whereNumber('salesOrderId');

// ==========================================
// Sales Order Management Routes (WMS admin)
// ==========================================
Route::get('sales-orders/{salesOrderId}/details', [SalesOrderController::class, 'getSalesOrderDetails'])->whereNumber('salesOrderId');
Route::get('sales-orders', [SalesOrderController::class, 'getAllSalesOrders']);
Route::post('sales-orders/{id}/accept', [SalesOrderController::class, 'acceptSalesOrder'])->whereNumber('id');
Route::post('sales-orders/{id}/cancel', [SalesOrderController::class, 'cancelSalesOrder'])->whereNumber('id');

// ==========================================
// Sales Indent Routes
// ==========================================
Route::get('sales-indents', [SalesIndentController::class, 'getAllSalesIndents']);
Route::post('sales-indents/purchase-indent', [PurchaseIndentController::class, 'createPurchaseIndent']);

// ==========================================
// Purchase Indent Routes
// ==========================================
Route::get('purchase-indents', [PurchaseIndentController::class, 'getAllPurchaseIndents']);
Route::get('purchase-indents/{id}/pdf', [PurchaseIndentController::class, 'downloadPdf'])->whereNumber('id');
Route::post('purchase-indents/{id}/purchase-order', [PurchaseOrderController::class, 'createPurchaseOrder'])->whereNumber('id');

// ==========================================
// Purchase Order Routes
// ==========================================
Route::get('purchase-orders', [PurchaseOrderController::class, 'getAllPurchaseOrders']);
Route::post('purchase-orders/{id}/mark-sent', [PurchaseOrderController::class, 'markSent'])->whereNumber('id');
Route::post('purchase-orders/{id}/mark-procured', [PurchaseOrderController::class, 'markProcured'])->whereNumber('id');
