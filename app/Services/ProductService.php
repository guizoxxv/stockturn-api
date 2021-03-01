<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Filters\ProductFilter;
use Validator;
use Illuminate\Validation\ValidationException;
use App\Validation\Rules\Sortable;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(ProductFilter $filter)
    {
        $filterData = $filter->data();

        if (count($filterData) > 0) {
            $validator = Validator::make($filterData, [
                'name' => 'nullable|string|max:255',
                'price' => 'nullable|numeric|min:0',
                'page' => 'nullable|integer|min:1',
                'limit' => 'nullable|integer|min:1',
                'sort' => [new Sortable(['name', 'price'])],
            ]);

            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->all());
            }
        }

        return $this->productRepository->findAll($filter);
    }
}
