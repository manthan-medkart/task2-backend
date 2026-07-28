<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductPartialUpdateRequest;
use App\Http\Requests\ProductPublishRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Services\ProductService;

class ProductController extends ApiController
{
    private ProductService $productService;
    public function __construct(
        ProductService $productService
    ){
        $this->productService = $productService;
    }


//======================================================================================================================
//================  CREATE PRODUCT  ======================================================================================================
//======================================================================================================================

    public function createProduct(ProductCreateRequest $request){
        try{
            $this->productService->createProduct($request->validated());
            return $this->resp('Product created', 201, null);
        }catch (\Exception $exception){
            return $this->resp('Product not created', 500, 'Internal Server Error');
        }
    }


//======================================================================================================================
//================  UPDATE PARTIAL PRODUCT  ======================================================================================================
//======================================================================================================================

    public function updatePartialProduct(int $id, ProductPartialUpdateRequest $request)
    {
        try{
            $product = $this->productService->updatePartialProduct($id, $request->validated());
            return $this->resp('Product updated', 200, new ProductResource($product));
        }catch (\Exception $exception){
            return $this->resp('Product not updated', 500, 'Internal Server Error');
        }
    }


//======================================================================================================================
//================  UPDATE PRODUCT  ======================================================================================================
//======================================================================================================================

    public function updateProduct(int $id, ProductUpdateRequest $request){
        try{
            $product = $this->productService->updateProduct($id, $request->validated());
            return $this->resp('Product updated', 200, new ProductResource($product));
        }
        catch (\Exception $exception){
            return $this->resp('Product not updated', 500, 'Internal Server Error');
        }
    }


//======================================================================================================================
//================  GET ALL PRODUCTS  ======================================================================================================
//======================================================================================================================

    public function getAllProducts()
    {
        try{
            $products = $this->productService->getAllProducts();
            return $this->resp('All products', 200, new ProductResource($products));
        }
        catch(\Exception $e){
            return $this->resp('Products not found', 404, "Failed to retrieve products");
        }
    }


//======================================================================================================================
//================  GET PRODUCT BY ID  ======================================================================================================
//======================================================================================================================

    public function getProduct(int $id)
    {
        try{
            $product = $this->productService->getProduct($id);
            return $this->resp('Product found', 200 , new ProductResource($product));
        }
        catch(\Exception $e){
            return $this->resp('Product not found', 404 , 'Failed to retrieve product');
        }
    }

//======================================================================================================================
//================  PUBLISH PRODUCT  ======================================================================================================
//======================================================================================================================


    public function publishProduct(ProductPublishRequest $request)
    {
        try{
            $this->productService->publishProduct($request->validated());
            return $this->resp('Product published', 200, null);
        }
        catch (\Exception $exception){
            return $this->resp('Product not published', 500, 'Internal Server Error');
        }


    }


}
