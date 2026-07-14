<?php

namespace App\Models;

use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Type\Decimal;
use Ramsey\Uuid\Type\Integer;

class Product extends Model
{
    use HasFactory;
    public $table = 'products';

//     public String $name;
//     public String $composition;
//     public Decimal $mrp;
//     public Decimal $sales_rate;
//     public Integer $total_strip;
//     public Integer $medicine_per_strip;
//     public $image_url;


    protected $fillable = [
        'name',
        'composition',
        'mrp',
        'sales_rate',
        'total_strip',
        'medicine_per_strip',
        'image_url'
    ];


}
