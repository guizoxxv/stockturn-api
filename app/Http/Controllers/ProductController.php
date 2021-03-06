<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Filters\ProductFilter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): JsonResponse
    {
        $filter = new ProductFilter($request->all());

        $result = $this->productService->index($filter);

        return response()->json($result);
    }

    public function store(Request $request): JsonResponse
    {
        $result = $this->productService->store($request->all());

        return response()->json($result, 201);
    }

    public function show(string $productId): JsonResponse
    {
        if (!is_numeric($productId)) {
            throw new UnprocessableEntityHttpException('Unprocessable entity');
        }

        try {
            $result = $this->productService->show((int)$productId);

            return response()->json($result);
        } catch (\Exception $e) {
            if ($e instanceOf ModelNotFoundException) {
                throw new NotFoundHttpException('Not found');
            }

            throw $e;
        }
    }

    public function update(Request $request, string $productId): JsonResponse
    {
        if (!is_numeric($productId)) {
            throw new UnprocessableEntityHttpException('Unprocessable entity');
        }

        try {
            $result = $this->productService->update($request->all(), (int)$productId);

            return response()->json($result);
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                throw new NotFoundHttpException('Not found');
            }

            throw $e;
        }
    }

    public function destroy(string $productId): Response
    {
        if (!is_numeric($productId)) {
            throw new UnprocessableEntityHttpException('Unprocessable entity');
        }

        $this->productService->destroy((int)$productId);

        return response()->noContent();
    }

    public function bulkUpsert(Request $request): JsonResponse
    {
        $result = $this->productService->buckUpsert($request->all());

        return response()->json($result);
    }
}
