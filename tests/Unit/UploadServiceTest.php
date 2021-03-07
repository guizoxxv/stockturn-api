<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Upload;
use Illuminate\Http\UploadedFile;

class UploadServiceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;
    private UploadService $uploadService;

    public function setUp(): void
    {
        parent::setUp();

        $this->uploadService = app(UploadService::class);
    }

    public function test_index(): void
    {
        $result = $this->uploadService->index([]);

        $this->assertNotEmpty($result);
    }

    public function test_store(): void
    {
        $file = UploadedFile::fake()->create('sample.csv', 10, 'application/json');

        $result = $this->uploadService->store($file);

        $this->assertInstanceOf(Upload::class, $result);
    }
}
