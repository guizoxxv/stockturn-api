<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filters\ProductFilter;
use Illuminate\Validation\ValidationException;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private ProductService $productService;

    public function setUp(): void
    {
        parent::setUp();

        $this->productService = app(ProductService::class);
    }

    public function test_index_without_filter()
    {
        $filter = new ProductFilter([]);

        $result = $this->productService->index($filter);

        $this->assertNotEmpty($result);
    }

    public function test_index_with_filter()
    {
        $filter = new ProductFilter([
            'fromPrice' => 500,
        ]);

        $result = $this->productService->index($filter);

        $isValid = collect($result->items())->every(function ($item, $key) {
            return $item->price > 500;
        });

        $this->assertTrue($isValid);
    }

    public function test_index_validator()
    {
        $this->expectException(ValidationException::class);

        $filter = new ProductFilter([
            'page' => 0,
        ]);

        $this->productService->index($filter);
    }
}
