<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';

    protected $fillable = [
        'product_code',
        'quantity',
    ];

    public function product() : BelongsTo {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
}
