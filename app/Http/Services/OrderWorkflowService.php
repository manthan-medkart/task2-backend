<?php

namespace App\Http\Services;

use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesInvoice;
use App\Models\Delivery;
use App\Http\Services\StockService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderWorkflowService
{
    private StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Create a new Sales Order.
     *
     * @param array $data
     * @return SalesOrder
     */
    public function createSalesOrder(array $data): SalesOrder
    {
        return DB::transaction(function () use ($data) {
            $order = SalesOrder::create([
                'ecommerce_order_id' => $data['ecommerce_order_id'],
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'total_amount' => 0,
                'status' => 'pending'
            ]);

            $totalAmount = 0;

            foreach ($data['items'] as $itemData) {
                $product = Product::where('product_code', $itemData['product_code'])->first();
                if (!$product) {
                    throw new ModelNotFoundException("Product with code {$itemData['product_code']} not found.");
                }

                $itemPrice = $product->sale_rate;
                $itemTotal = $itemPrice * $itemData['quantity'];
                $totalAmount += $itemTotal;

                SalesOrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_code' => $product->product_code,
                    'quantity' => $itemData['quantity'],
                    'price' => $itemPrice
                ]);
            }

            $order->total_amount = $totalAmount;
            $order->save();

            return $order->load('items');
        });
    }

    /**
     * Generate invoice for a Sales Order.
     *
     * @param int $salesOrderId
     * @return SalesOrder
     */
    public function generateInvoice(int $salesOrderId): SalesOrder
    {
        return DB::transaction(function () use ($salesOrderId) {
            $order = SalesOrder::find($salesOrderId);
            if (!$order) {
                throw new ModelNotFoundException("Sales Order ID {$salesOrderId} not found.");
            }

            if ($order->status !== 'pending') {
                throw new Exception("Only pending orders can be invoiced. Current status: {$order->status}");
            }

            $invoiceNumber = 'INV-' . time() . rand(10, 99);

            SalesInvoice::create([
                'sales_order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'amount' => $order->total_amount,
                'status' => 'unpaid'
            ]);

            $order->status = 'invoiced';
            $order->save();

            return $order->load(['items', 'invoice']);
        });
    }

    /**
     * Process delivery for a Sales Order (updates stock).
     *
     * @param int $salesOrderId
     * @param array $data
     * @return SalesOrder
     */
    public function processDelivery(int $salesOrderId, array $data): SalesOrder
    {
        return DB::transaction(function () use ($salesOrderId, $data) {
            $order = SalesOrder::find($salesOrderId);
            if (!$order) {
                throw new ModelNotFoundException("Sales Order ID {$salesOrderId} not found.");
            }

            if ($order->status !== 'invoiced') {
                throw new Exception("Only invoiced orders can be delivered. Current status: {$order->status}");
            }

            // 1. Verify stock is available for all items in the order
            $orderItems = $order->items;
            foreach ($orderItems as $item) {
                $product = Product::where('product_code', $item->product_code)->first();
                if (!$product) {
                    throw new ModelNotFoundException("Product with code {$item->product_code} not found.");
                }

                if ($product->total_strip < $item->quantity) {
                    throw new Exception("Insufficient stock for product [{$product->name}] (Code: {$product->product_code}). Current stock: {$product->total_strip}, Requested: {$item->quantity}");
                }
            }

            // 2. Deduct stock and record logs
            foreach ($orderItems as $item) {
                $this->stockService->updateStock($item->product_code, [
                    'quantity' => $item->quantity,
                    'type' => 'deduction',
                    'description' => "Order fulfillment: {$order->ecommerce_order_id}"
                ]);
            }

            // 3. Create Delivery record
            $deliveryNumber = 'DEL-' . time() . rand(10, 99);
            Delivery::create([
                'sales_order_id' => $order->id,
                'delivery_number' => $deliveryNumber,
                'status' => 'delivered',
                'tracking_number' => $data['tracking_number'] ?? null
            ]);

            // 4. Update Sales Order status
            $order->status = 'delivered';
            $order->save();

            return $order->load(['items', 'invoice', 'delivery']);
        });
    }

    /**
     * Get details of a Sales Order.
     *
     * @param int $salesOrderId
     * @return SalesOrder
     */
    public function getSalesOrderDetails(int $salesOrderId): SalesOrder
    {
        $order = SalesOrder::find($salesOrderId);
        if (!$order) {
            throw new ModelNotFoundException("Sales Order ID {$salesOrderId} not found.");
        }

        return $order->load(['items', 'invoice', 'delivery']);
    }
}
