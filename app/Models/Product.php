<?php

namespace App\Models;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Decimal;
use Ramsey\Uuid\Type\Integer;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    /**
     * Indicates whether attributes are snake_cased on serialization.
     *
     * @var bool
     */
    public static $snakeAttributes = false;
    public $table = 'products';


//     public String $name;
//     public String $composition;
//     public Decimal $mrp;
//     public Decimal $sales_rate;
//     public Integer $total_strip;
//     public Integer $medicine_per_strip;
//     public $image_url;


    protected $fillable = [
        'product_code',
        'name',
        'composition',
        'mrp',
        'sale_rate',
        'total_strip',
        'medicine_per_strip',
        'image_url'
    ];

    public function stockLogs(): HasMany
    {
        return $this->hasMany(StockLog::class, 'product_code', 'product_code');
    }

    public function getAvailabilityAttribute(): string
    {
        return $this->total_strip > 0 ? 'available' : 'unavailable';
    }
}
