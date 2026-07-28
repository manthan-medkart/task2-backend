<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


$routeDirectory = __DIR__.DIRECTORY_SEPARATOR.'ecom-routes'.DIRECTORY_SEPARATOR;

require $routeDirectory.'ProductRoutes.php';
require $routeDirectory.'PurchaseIndentRoutes.php';
require $routeDirectory.'PurchaseOrderRoutes.php';
require $routeDirectory.'SalesIndentRoutes.php';
require $routeDirectory.'SalesOrdersRoutes.php';
require $routeDirectory.'StockRoutes.php';
require $routeDirectory.'StockMovementRoutes.php';
