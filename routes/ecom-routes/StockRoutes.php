<?php


use App\Http\Controllers\StockController;

// ==========================================
// Stock Routes
// ==========================================

Route::post('stock/product/create', [StockController::class, 'createStockOfNewProduct'])->name('stock.product.create');
Route::post('stock/adjust', [StockController::class, 'adjustStock']);
Route::get('stock', [StockController::class, 'getStockOfAllProducts']);
Route::get('stock/product/{productCode}', [StockController::class, 'getStockByProductCode'])->whereNumber('productCode');
Route::patch('stock/product/deduct', [StockController::class, 'deductStockByProductCode']);
Route::patch('stock/product/add', [StockController::class, 'addStockByProductCode']);
Route::get('stock/product/{productCode}/available', [StockController::class, 'availabilityOfProductStock'])->whereNumber('productCode');
