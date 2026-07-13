<?php

namespace App\Models;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $table = 'products';

     public String $name;
     public String $composition;
     public BigInteger $mrp;
     public BigInteger $sale_rate;
     public BigInteger $total_strip;
     public BigInteger $medicine_per_strip;
     public String $image_url;


    protected $fillable = [
        'name',
        'composition',
        'mrp',
        'sale_rate',
        'total_strip',
        'medicine_per_strip',
        'image_url'
    ];


}
