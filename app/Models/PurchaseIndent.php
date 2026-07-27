<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseIndent extends Model
{
    use HasFactory;

    protected $table = 'purchase_indents';

    protected $fillable = [
        'indent_number',
        'status',
    ];

    /**
     * The items in this purchase indent.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseIndentItem::class, 'purchase_indent_id');
    }

    /**
     * The purchase order created from this indent.
     */
    public function purchaseOrder(): HasOne
    {
        return $this->hasOne(PurchaseOrder::class, 'purchase_indent_id');
    }
}
