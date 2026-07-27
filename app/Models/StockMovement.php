<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';

    protected $fillable = [
        'product_code',
        'movement_type',
        'quantity_change',
        'before_quantity',
        'after_quantity',
        'source',
        'updated_by',
    ];

    public function product() : BelongsTo {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
}
