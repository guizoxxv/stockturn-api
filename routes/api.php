<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return response()->json([
       'application' => config('app.name'),
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('products', ProductController::class)
    ->except([
        'create',
        'edit',
    ])
    ->middleware('auth:sanctum');

Route::post('/products/actions/bulk-upsert', [ProductController::class, 'bulkUpsert']);

Route::resource('uploads', UploadController::class)
    ->except([
        'create',
        'edit',
        'show',
        'update',
        'destroy',
    ])
    ->middleware('auth:sanctum');

Route::post('uploads/process-csv/{productId}', [UploadController::class, 'processCsv'])
    ->middleware('auth:sanctum');
