<?php

namespace App\Http\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function createProduct(array $data) : Product
    {
        return DB::transaction(function () use ($data) {
            return Product::create($data);
        });


    }
}
