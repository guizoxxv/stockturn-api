<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UploadControllerTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    protected bool $seed = true;

    public function testIndex(): void
    {
        $response = $this->get('/api/uploads');

        $response->assertStatus(200);
    }

    public function testStoreInvalid(): void
    {
        $response = $this->postJson('api/uploads');

        $response->assertStatus(422);
    }
}
