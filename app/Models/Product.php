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


    protected $fillable = [
        'name',
        'composition',
        'mrp',
        'total_strip',
        'sales_rate',
        'medicine_per_strip',
        'image_url'
    ];

    public function getAvailabilityAttribute(): string
    {
        return $this->total_strip > 0 ? 'available' : 'unavailable';
    }
}
