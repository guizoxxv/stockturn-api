<?php

namespace App\Repositories;

use App\Models\Product;
use App\Filters\ProductFilter;

class ProductRepository
{
    public function findAll(ProductFilter $filter)
    {
        $filterData = $filter->data();

        $limit = $filterData['limit'] ?? 3;

        return Product::filter($filter)->orderBy('id')->paginate($limit);
    }

    public function find(int $productId): Product
    {
        return Product::findOrFail($productId);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(array $data, int $productId): Product
    {
        return tap(Product::findOrFail($productId))->update($data);
    }

    public function destroy(int $productId): int
    {
        return Product::destroy($productId);
    }

    public function upsert(int $id = null, array $data): Product
    {
        return Product::updateOrCreate(
            ['id' => $id],
            $data,
        );
    }
}
