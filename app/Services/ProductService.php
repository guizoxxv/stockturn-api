<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Filters\ProductFilter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Validation\Rules\Sortable;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(ProductFilter $filter): LengthAwarePaginator
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

    public function show(int $productId): Product
    {
        return $this->productRepository->find($productId);
    }

    public function store(array $data): Product
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->all());
        }

        return $this->productRepository->create($data);
    }

    public function update(array $data, int $productId): Product
    {
        $validator = Validator::make($data, [
            'name' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->all());
        }

        return $this->productRepository->update($data, $productId);
    }

    public function destroy(int $productId): int
    {
        return $this->productRepository->destroy($productId);
    }
}
