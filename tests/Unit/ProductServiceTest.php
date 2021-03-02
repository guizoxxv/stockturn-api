<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filters\ProductFilter;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Product;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;
    private ProductService $productService;

    public function setUp(): void
    {
        parent::setUp();

        $this->productService = app(ProductService::class);
    }

    public function test_index_without_filter(): void
    {
        $filter = new ProductFilter([]);

        $result = $this->productService->index($filter);

        $this->assertNotEmpty($result);
    }

    public function test_index_with_filter(): void
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

    public function test_index_invalid(): void
    {
        $this->expectException(ValidationException::class);

        $filter = new ProductFilter([
            'page' => 0,
        ]);

        $this->productService->index($filter);
    }

    public function test_show(): void
    {
        $result = $this->productService->show(1);

        $this->assertNotNull($result);
    }

    public function test_show_404(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->productService->show(0);
    }

    public function test_destroy(): void
    {
        $result = $this->productService->destroy(1);

        $this->assertEquals(1, $result);
    }

    public function test_destroy_inexistent(): void
    {
        $result = $this->productService->destroy(0);

        $this->assertEquals(0, $result);
    }

    public function test_store(): void
    {
        $result = $this->productService->store([
            'name' => 'Product A',
            'sku' => uniqid(),
            'price' => 10.00,
        ]);

        $this->assertInstanceOf(Product::class, $result);
    }

    public function test_store_invalid(): void
    {
        $this->expectException(ValidationException::class);

        $this->productService->store([
            'name' => 'Product A',
        ]);
    }

    public function test_store_invalid_stockTimeline(): void
    {
        $this->expectException(ValidationException::class);

        $this->productService->store([
            'name' => 'Product A',
            'sku' => uniqid(),
            'price' => 10.00,
            'stockTimeline' => [
                [
                    'stock' => 10,
                    'date' => '2021-03-02', // invalid date format
                ]
            ]
        ]);
    }

    public function test_store_unique_sku(): void
    {
        $this->expectException(ValidationException::class);

        $sku = Product::first()->sku;

        $this->productService->store([
            'name' => 'Product A',
            'sku' => $sku,
        ]);
    }

    public function test_update(): void
    {
        $result = $this->productService->update(
            [
                'name' => 'Product 2',
            ],
            1,
        );

        $this->assertEquals('Product 2', $result->name);
    }

    public function test_update_invalid(): void
    {
        $this->expectException(ValidationException::class);

        $this->productService->update(
            [
                'price' => -10,
            ],
            1
        );
    }

    public function test_update_404(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $this->productService->update(
            [
                'name' => 'Product 2',
            ],
            0
        );
    }

    public function test_buckUpsert(): void
    {
        $products = [
            'products' => [
                [
                    'id' => 1,
                    'name' => 'Product 1_2',
                ],
                [
                    'name' => 'Product 2',
                    'sku' => '123',
                    'price' => 10.00
                ],
            ],
        ];

        $result = $this->productService->buckUpsert($products);

        $expected = [
            'created' => 1,
            'updated' => 1,
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_buckUpsert_invalid(): void
    {
        $this->expectException(ValidationException::class);

        $products = [
            'products' => [
                [
                    'id' => 0,
                    'name' => 'Product 1_2',
                ],
            ],
        ];

        $this->productService->buckUpsert($products);
    }
}
