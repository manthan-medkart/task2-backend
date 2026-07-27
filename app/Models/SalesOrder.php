<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrder extends Model
{
    use HasFactory;

    protected $table = 'sales_orders';

    protected $fillable = [
        'ecommerce_order_id',
        'customer_name',
        'customer_email',
        'total_amount',
        'status',
    ];

    /**
     * The order items for this sales order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class, 'sales_order_id');
    }

    /**
     * The sales indents created when stock is unavailable.
     */
//    public function salesIndents(): HasMany
//    {
//        return $this->hasMany(SalesIndent::class, 'sales_order_id');
//    }
}
