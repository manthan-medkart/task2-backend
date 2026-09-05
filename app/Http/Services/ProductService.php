<?php

namespace App\Http\Services;

use App\Models\Product;
use App\Models\Stock;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProductService
{
    public function createProduct(array $data)
    {
        DB::transaction(function () use ($data) {
            $product = Product::create($data);
            $product->refresh();

            //This will create Stock of new product
            Stock::create([
                'product_code' => $product->product_code,
                'quantity' => $data['total_strip'],
            ]);
        });
    }

    public function updatePartialProduct(int $productCode, array $data) : Product
    {
        return DB::transaction(function () use ($productCode, $data) {
            $product = $this->productExists($productCode);
            $product->update($data);
            return $product;
        });
    }

    public function updateProduct(int $productCode, array $data) : Product
    {
        return DB::transaction(function () use ($productCode, $data) {
            $product = $this->productExists($productCode);
            $product->update($data);
            return $product;
        });
    }

    public function getAllProducts() : Collection
    {
        return DB::transaction(function () {
            return Product::all();
        });
    }

    public function getProduct(int $product_code) : Product
    {
        $product = DB::transaction(function () use ($product_code) {
             return $this->productExists($product_code);

        });
        return $product;
    }
    public function productExists(int $productCode) : Product
    {
        $product = Product::where('product_code', $productCode)->first();
        if(!$product){
            throw new ModelNotFoundException('Product not found');
        }
        return $product;
    }

    public function publishProduct(array $product)
    {
        $productCode = $product['product_code'];
        try{
            Http::post("http://localhost:8080/api/products/publish/{$productCode}", $product);
            return $product;

        }catch (Exception $exception) {
            echo $exception->getMessage();
        }
        return null;
    }
}
