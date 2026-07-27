<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SalesIndent extends Model
{
    use HasFactory;

    protected $table = 'sales_indents';

    protected $fillable = [
        'sales_order_id',
        'product_code',
        'product_name',
        'quantity',
        'status',
    ];

    /**
     * The sales order this indent belongs to.
     */
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }

    /**
     * The product this indent is for.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }

    /**
     * The purchase indent item created from this sales indent.
     */
    public function purchaseIndentItem(): HasOne
    {
        return $this->hasOne(PurchaseIndentItem::class, 'sales_indent_id');
    }
}
