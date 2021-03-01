<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function findAll()
    {
        return Product::paginate(20);
    }
}
