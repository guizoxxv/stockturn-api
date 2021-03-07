<?php

namespace App\Http\Controllers;

use App\Services\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UploadController extends Controller
{
    private UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function index(Request $request): JsonResponse
    {
        $result = $this->uploadService->index($request->all());

        return response()->json($result);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:csv|max:2000',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->all());
        }

        $file = $request->file('file');

        $result = $this->uploadService->store($file);

        return response()->json($result, 201);
    }

    public function processCsv(int $productId): JsonResponse
    {
        if (!is_numeric($productId)) {
            throw new UnprocessableEntityHttpException('Unprocessable entity');
        }

        $result = $this->uploadService->processCsv($productId);

        return response()->json($result, 200);
    }
}
