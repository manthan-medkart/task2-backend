<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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
Route::patch('product/update/{id}', [ProductController::class, 'updatePartialProduct']);
Route::put('product/update/{id}', [ProductController::class, 'updateProduct']);
Route::get('product', [ProductController::class, 'getAllProducts']);
Route::get('product/details/{id}', [ProductController::class, 'getProduct']);
//Route::post('product/publish/{id}',[ProductController::class, 'publishProduct']);
