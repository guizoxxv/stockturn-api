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
                'fromPrice' => 'nullable|numeric|min:0',
                'toPrice' => 'nullable|numeric|min:0',
                'fromDate' => 'nullable|date|date_format:Y-m-d',
                'toDate' => 'nullable|date|date_format:Y-m-d',
                'page' => 'nullable|integer|min:1',
                'limit' => 'nullable|integer|min:1',
                'sort' => [
                    new Sortable([
                        'name',
                        'sku',
                        'price',
                        'stock',
                        'created_at',
                        'updated_at',
                    ])
                ],
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
            'sku' => 'required|string|max:255|unique:products',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'stockTimeline' => ['nullable','array'],
            'stockTimeline.*.stock' => 'required|integer|min:0',
            'stockTimeline.*.date' => 'required|date_format:Y-m-d H:i',
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
            'sku' => 'nullable|string|max:255|unique:products',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'stockTimeline' => ['nullable', 'array'],
            'stockTimeline.*.stock' => 'required|integer|min:0',
            'stockTimeline.*.date' => 'required|date_format:Y-m-d H:i',
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

    public function buckUpsert(array $data): array
    {
        $created = 0;
        $updated = 0;

        $validator = Validator::make($data, [
            'products' => 'required|array',
            'products.*.id' => 'nullable|integer|exists:products',
            'products.*.name' => 'nullable|string|max:255',
            'products.*.sku' => 'nullable|string|max:255',
            'products.*.price' => 'nullable|numeric|min:0',
            'products.*.stock' => 'nullable|integer|min:0',
            'products.*.stockTimeline' => ['nullable', 'array'],
            'products.*.stockTimeline.*.stock' => 'required|integer|min:0',
            'products.*.stockTimeline.*.date' => 'required|date_format:Y-m-d H:i',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->all());
        }

        foreach ($data['products'] as $item) {
            $id = $item['id'] ?? null;

            unset($item['id']);

            $result = $this->productRepository->upsert($id, $item);

            if ($result->wasRecentlyCreated) {
                $created++;
            } else {
                $updated++;
            }
        }

        return [
            'created' => $created,
            'updated' => $updated,
        ];
    }
}
