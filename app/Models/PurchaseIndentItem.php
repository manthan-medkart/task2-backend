<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseIndentItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_indent_items';

    protected $fillable = [
        'purchase_indent_id',
        'sales_indent_id',
        'product_code',
        'quantity',
    ];

    /**
     * The purchase indent this item belongs to.
     */
    public function purchaseIndent(): BelongsTo
    {
        return $this->belongsTo(PurchaseIndent::class, 'purchase_indent_id');
    }

    /**
     * The sales indent this item was created from.
     */
    public function salesIndent(): BelongsTo
    {
        return $this->belongsTo(SalesIndent::class, 'sales_indent_id');
    }

    /**
     * The product for this item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
}
