<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OrderWorkflowController;

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


Route::post('product/create', [ProductController::class, 'createProduct']);
Route::patch('product/update/{id}', [ProductController::class, 'updatePartialProduct'])->whereNumber('id');
Route::put('product/update/{id}', [ProductController::class, 'updateProduct'])->whereNumber('id');
Route::get('product', [ProductController::class, 'getAllProducts']);
Route::get('product/details/{id}', [ProductController::class, 'getProduct'])->whereNumber('id');
//Route::post('product/publish/{id}',[ProductController::class, 'publishProduct']);

Route::post('product/{productCode}/stock/update', [StockController::class, 'updateStock'])->whereNumber('productCode');
Route::get('product/{productCode}/stock/history', [StockController::class, 'getStockHistory'])->whereNumber('productCode');

Route::post('sales-order/create', [OrderWorkflowController::class, 'createSalesOrder']);
Route::post('sales-order/{id}/invoice', [OrderWorkflowController::class, 'generateInvoice'])->whereNumber('id');
Route::post('sales-order/{id}/delivery', [OrderWorkflowController::class, 'processDelivery'])->whereNumber('id');
Route::get('sales-order/{id}/details', [OrderWorkflowController::class, 'getSalesOrderDetails'])->whereNumber('id');
