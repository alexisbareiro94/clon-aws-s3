<?php

use App\Http\Controllers\BucketController;
use App\Http\Controllers\ObjectController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum', 'throttle:60,1')->group(function () {
    Route::get('/oss/buckets', [BucketController::class, 'index'])->middleware('ability:view');
    Route::get('/oss/{bucket:slug}', [BucketController::class, 'show'])->middleware('ability:view');
    Route::post('/oss/buckets', [BucketController::class, 'store'])->middleware('ability:create');
    Route::put('/oss/{bucket:slug}', [BucketController::class, 'update'])->middleware('ability:update');
    Route::delete('/oss/{bucket:slug}', [BucketController::class, 'destroy'])->middleware('ability:delete');

    Route::get('/oss/{bucket:slug}/{objecto:original_name}', [ObjectController::class, 'show'])->middleware('ability:view');
    Route::get('/oss/{bucket:slug}/{objecto:original_name}/view', [ObjectController::class, 'viewFile'])->middleware('ability:view');
    Route::post('/oss/{bucket:slug}/object', [ObjectController::class, 'store'])->middleware('ability:create');
    Route::put('/oss/{bucket:slug}/{objecto:original_name}', [ObjectController::class, 'update'])->middleware('ability:update');
    Route::delete('/oss/{bucket:slug}/{objecto:original_name}', [ObjectController::class, 'destroy'])->middleware('ability:delete');
});
