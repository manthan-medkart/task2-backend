<?php

namespace App\Http\Services;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductServices
{
    public function createProduct(Request $request) : Product
    {
        return Product::create([
            'name' => $request->name,
            'composition' => $request->compostion,
            'mrp' => $request->mrp,
            'sale_rate' => $request->sale_rate,
            'total_strip' => $request->total_strip,
            'medicine_per_strip' => $request->medicine_per_strip,
            'image_url' => $request->image_url,
        ]);
    }
}
