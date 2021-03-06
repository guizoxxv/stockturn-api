<?php

namespace App\Services;

use App\Jobs\ProcessCsvJob;
use App\Models\Upload;
use App\Repositories\UploadRepository;
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

        $upload = $this->uploadRepository->create($file, $path);

        if ($upload->exists) {
            ProcessCsvJob::dispatch($upload->id);
        }

        return $upload;
    }

    public function processCsv(): Upload {
        $upload = $this->uploadRepository->find(1);

        ProcessCsvJob::dispatch($upload->id);

        return $upload;
    }
}
