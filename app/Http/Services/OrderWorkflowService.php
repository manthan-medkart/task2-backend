<?php

namespace App\Http\Services;

use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Http\Services\StockService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;

class OrderWorkflowService
{
    public function createSalesOrder(array $data): SalesOrder
    {
        return DB::transaction(function () use ($data) {
            $order = SalesOrder::create([
                'ecommerce_order_id' => $data['ecommerce_order_id'],
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'total_amount' => 0,
                'status' => 'PENDING'
            ]);


            foreach($data['items'] as $item){
                $product = Product::where('product_code', $item['product_code'])->first();
                if(!$product){
                    throw new ModelNotFoundException("Product with product code {$item['product_code']} not found");
                }
                $order->total_amount += $item['quantity'] * $product->sales_rate;
                SalesOrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_code' => $item['product_code'],
                    'quantity' => $item['quantity'],
                    'price' => $product->sales_rate,
                ]);
            }
            $order->save();
            return $order;
        });
    }

//======================================================================================================================
//================  CHANGE ORDER STATUS AT ECOMMERCE SIDE  =============================================================
//======================================================================================================================

    public function notifyEcommerce(string $ecommerceOrderId, string $status)
    {
        try {
//            $ecommerceUrl = env('ECOMMERCE_API_URL', 'http://localhost:8080');
            $response = Http::post(
                "http://localhost:8080/api/orders/update-status/{$ecommerceOrderId}?status={$status}"
            );
            if ($response->status() == 404) {
                Log::error(
                    "Failed to update status in E-Commerce. Status code: "
                    . $response->status() . " Response: " . $response->body()
                );
                throw new Exception($response->json('message'));
            }
        } catch (Exception $e) {
            Log::error("Failed to notify E-Commerce system: " . $e->getMessage());
            throw new Exception($e->getMessage());
        }
    }

//    public function getSalesOrderDetails(int $salesOrderId): SalesOrder
//    {
//        $order = SalesOrder::find($salesOrderId);
//        if (!$order) {
//            throw new ModelNotFoundException("Sales Order ID {$salesOrderId} not found.");
//        }
//
//        return $order->load(['items']);
//    }
}
