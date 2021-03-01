<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_index()
    {
        $result = $this->productService->index();

        $this->assertNotEmpty($result);
    }
}
