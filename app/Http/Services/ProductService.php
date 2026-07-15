<?php

namespace App\Http\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProductService
{
    public function createProduct(array $data) : Product
    {
        return DB::transaction(function () use ($data) {
            return Product::create($data);
        });
    }

    public function updatePartialProduct(int $id, array $data) : Product
    {
        return DB::transaction(function () use ($id, $data) {
            $product = $this->productExists($id);
            $product->update($data);
            return $product;
        });
    }

    public function updateProduct(int $id, array $data) : Product
    {
        return DB::transaction(function () use ($id, $data) {
            $product = $this->productExists($id);
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

    public function getProduct(int $id) : Product
    {
        return DB::transaction(function () use ($id) {
            return $this->productExists($id);
        });

    }
    public function productExists(int $id) : Product
    {
        $product = Product::find($id);
        if(!$product){
            throw new ModelNotFoundException('Product not found');
        }
        return $product;
    }

//    public function publishProduct(int $id, mixed $validated)
//    {
//        try{
//            Http::post('http:localhost:8080/api/product/publish/{id}', $product)
//
//        }catch (Exception $exception){
//
//        }
//
//    }
}
