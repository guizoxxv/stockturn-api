<?php

namespace App\Services;

use App\Jobs\ProcessCsvJob;
use App\Models\Upload;
use App\Repositories\UploadRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UploadService
{
    private $uploadRepository;

    public function __construct(UploadRepository $uploadRepository)
    {
        $this->uploadRepository = $uploadRepository;
    }

    public function index(array $data): LengthAwarePaginator
    {
        if (count($data) > 0) {
            $validator = Validator::make($data, [
                'page' => 'nullable|integer|min:1',
                'limit' => 'nullable|integer|min:1',
            ]);

            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->all());
            }
        }

        return $this->uploadRepository->findAll($data);
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

    public function processCsv(): Upload
    {
        $upload = $this->uploadRepository->find(1);

        ProcessCsvJob::dispatch($upload->id);

        return $upload;
    }
}
