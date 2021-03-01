<?php

namespace App\Repositories;

use App\Models\Product;
use App\Filters\ProductFilter;

class ProductRepository
{
    public function findAll(ProductFilter $filter)
    {
        $filterData = $filter->data();

        $limit = $filterData['limit'] ?? 20;

        return Product::filter($filter)->paginate($limit);
    }

    public function find(int $productId): Product
    {
        return Product::findOrFail($productId);
    }
}
