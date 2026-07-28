<?php

use App\Http\Controllers\StockMovementController;


// ==========================================
// Stock Movements
// ==========================================

Route::get('stock-movement', [StockMovementController::class, 'getAllStockMovements']);
Route::post('stock-movement/create', [StockMovementController::class, 'createStockMovement']);

