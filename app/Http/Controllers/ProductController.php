<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Services\ProductService;

class ProductController extends Controller
{
    private ProductService $productService;
    public function __construct(
        ProductService $productService
    ){
        $this->productService = $productService;
    }

    public function createProduct(ProductCreateRequest $request){

        $product = $this->productService->createProduct($request->validated());

        return ApiResponse::success(
            'Product created successfully',
            '200',
            new ProductResource($product)
        );
    }


}
