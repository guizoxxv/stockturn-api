<?php

namespace App\Repositories;

use App\Models\Upload;
use Illuminate\Http\UploadedFile;

class UploadRepository
{
    public function create(UploadedFile $file, string $path): Upload
    {
        return Upload::create([
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
        ]);
    }
}
