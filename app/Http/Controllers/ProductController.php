<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductServices;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function createProduct(ProductServices $productServices, Request $request){

        $productServices->createProduct($request);


        return response()->json([
            "message" => "Product created"
        ]);

    }
}
