<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'po_number',
        'purchase_indent_id',
        'status',
    ];

    /**
     * The purchase indent this PO was created from.
     */
    public function purchaseIndent(): BelongsTo
    {
        return $this->belongsTo(PurchaseIndent::class, 'purchase_indent_id');
    }

    /**
     * The items in this purchase order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }
}
