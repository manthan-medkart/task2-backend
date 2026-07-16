<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductPartialUpdateRequest;
use App\Http\Requests\ProductPublishRequest;
use App\Http\Requests\ProductUpdateRequest;
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

        $this->productService->createProduct($request->validated());

        return ApiResponse::success(
            'Product created successfully',
            200,
            null
        );
    }

    public function updatePartialProduct(int $id, ProductPartialUpdateRequest $request)
    {
        $product = $this->productService->updatePartialProduct($id, $request->validated());
        return ApiResponse::success(
            'Product updated successfully',
            200,
            new ProductResource($product)
        );
    }

    public function updateProduct(int $id, ProductUpdateRequest $request){
        $product = $this->productService->updateProduct($id, $request->validated());
        return ApiResponse::success(
            'Product updated successfully',
            200,
            new ProductResource($product)
        );
    }

    public function getAllProducts()
    {
        $products = $this->productService->getAllProducts();
//        foreach($products as $product){
//            echo $product->id . '<br>';
//        }

        return ApiResponse::success(
            'Products retrieved successfully',
            200,
            new ProductResource($products)
        );
    }

    public function getProduct(int $id)
    {
        $product = $this->productService->getProduct($id);

        return ApiResponse::success(
            'Product retrieved successfully',
            200,
            new ProductResource($product)
        );
    }

    public function publishProduct(ProductPublishRequest $request)
    {
        $this->productService->publishProduct($request->validated());

    }


}
