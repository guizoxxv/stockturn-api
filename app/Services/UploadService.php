<?php

namespace App\Services;

use App\Models\Upload;
use App\Repositories\UploadRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class UploadService
{
    private $uploadRepository;

    public function __construct(UploadRepository $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }

    public function store(UploadedFile $file): Upload
    {
        $path = $file->store('uploads');

        return $this->uploadRepository->create($file, $path);
    }
}
