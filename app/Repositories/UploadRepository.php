<?php

namespace App\Repositories;

use App\Models\Upload;
use Illuminate\Http\UploadedFile;

class UploadRepository
{

    public function findAll(array $data)
    {
        $limit = $data['limit'] ?? 20;

        return Upload::orderBy('id')->paginate($limit);
    }

    public function find(int $uploadId): Upload
    {
        return Upload::findOrFail($uploadId);
    }

    public function create(UploadedFile $file, string $path): Upload
    {
        return Upload::create([
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
            'status' => 'CREATED',
        ]);
    }

    public function update(array $data, int $uploadId): Upload
    {
        return tap(Upload::findOrFail($uploadId))->update($data);
    }
}
