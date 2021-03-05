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
        $response = $this->get('/api/products?fromDate=2020-02-01');

        $response->assertStatus(200);
    }

    public function testIndexInvalid(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/products?fromDate=02/03/2021'); // invalid date format

        $response->assertStatus(422);
    }

    public function testShow(): void
    {
        $response = $this->get('api/products/1');

        $response->assertStatus(200);
    }

    public function testShow404(): void
    {
        $response = $this->get('api/products/0');

        $response->assertStatus(404);
    }

    public function testStore(): void
    {
        $response = $this->postJson('api/products', [
            'name' => 'Product 1',
            'price' => 10.00,
        ]);

        $response->assertStatus(201);
    }

    public function testStoreInvalid(): void
    {
        $response = $this->postJson('api/products', [
            'name' => 'Product 1',
        ]);

        $response->assertStatus(422);
    }

    public function testUpdate(): void
    {
        $response = $this->putJson('api/products/1', [
            'name' => 'Product 1_2',
        ]);

        $response->assertStatus(200);
    }

    public function testUpdateInvalid(): void
    {
        $response = $this->putJson('api/products/1', [
                'name' => 'Product 1_3',
                'price' => -10,
            ]);

        $response->assertStatus(422);
    }

    public function testUpdate404(): void
    {
        $response = $this->putJson('api/products/0', [
            'name' => 'Product 1_2',
        ]);

        $response->assertStatus(404);
    }

    public function testDestroy(): void
    {
        $response = $this->delete('api/products/1');

        $response->assertStatus(204);
    }

    public function testDestroyInexistent(): void
    {
        $response = $this->delete('api/products/0');

        $response->assertStatus(204);
    }

    public function testBulkUpsert(): void
    {
        $products = [
            'products' => [
                [
                    'id' => 1,
                    'name' => 'Product 1_2',
                ],
                [
                    'name' => 'Product 2',
                    'price' => 10.00
                ],
            ],
        ];

        $response = $this->postJson('api/products/bulk', $products);

        $response->assertStatus(200);
    }

    public function testBulkUpsertInvalid(): void
    {
        $products = [
            'products' => [
                [
                    'id' => 0,
                    'name' => 'Product 1_2',
                ],
            ],
        ];

        $response = $this->postJson('api/products/bulk', $products);

        $response->assertStatus(422);
    }
}
