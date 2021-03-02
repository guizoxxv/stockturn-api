<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    protected bool $seed = true;

    public function testIndex(): void
    {
        $response = $this->get('/api/products');

        $response->assertStatus(200);
    }

    public function testShow(): void
    {
        $response = $this->get('api/products/1');

        $response->assertStatus(200);
    }

    public function testStore(): void
    {
        $response = $this->postJson('api/products', [
            'name' => 'Product 1',
            'sku' => uniqid(),
            'price' => 10.00,
        ]);

        $response->assertStatus(201);
    }

    public function testUpdate(): void
    {
        $response = $this->putJson('api/products/1', [
            'name' => 'Product 1_2',
        ]);

        $response->assertStatus(200);
    }

    public function testDestroy(): void
    {
        $response = $this->delete('api/products/1');

        $response->assertStatus(204);
    }
}
