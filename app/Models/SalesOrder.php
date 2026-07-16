<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class, 'sales_order_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(SalesInvoice::class, 'sales_order_id');
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class, 'sales_order_id');
    }
}
