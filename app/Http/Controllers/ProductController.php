<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Filters\ProductFilter;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): Response
    {
        $filter = new ProductFilter($request->all());

        return $this->productService->index($filter);
    }

    public function store(Request $request): Response
    {
        //
    }

    public function show(string $productId): Product
    {
        if (!is_numeric($productId)) {
            abort(400, 'Bad request');
        }

        return $this->productService->show((int)$productId);
    }

    public function update(Request $request, Product $product): Response
    {
        //
    }

    public function destroy(string $productId): Response
    {
        if (!is_numeric($productId)) {
            abort(400, 'Bad request');
        }

        $this->productService->destroy((int)$productId);

        return response()->noContent();
    }
}
