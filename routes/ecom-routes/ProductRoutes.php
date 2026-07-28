<?php

use App\Http\Controllers\ProductController;

// ==========================================
// Product Routes
// ==========================================

Route::post('product/create', [ProductController::class, 'createProduct']);
Route::patch('product/update/{productCode}', [ProductController::class, 'updatePartialProduct'])->whereNumber('productCode');
Route::put('product/update/{productCode}', [ProductController::class, 'updateProduct'])->whereNumber('productCode');
Route::get('product', [ProductController::class, 'getAllProducts']);
Route::get('product/details/{productCode}', [ProductController::class, 'getProduct'])->whereNumber('productCode');
Route::post('product/publish/',[ProductController::class, 'publishProduct']);
