<?php

use App\Http\Controllers\Api\ApplicationApiController;
use App\Http\Controllers\Api\JobPostingApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'tenant.token', 'throttle:api'])->group(function () {
    Route::get('/jobs', [JobPostingApiController::class, 'index']);
    Route::post('/jobs', [JobPostingApiController::class, 'store']);
    Route::get('/jobs/{id}', [JobPostingApiController::class, 'show']);

    Route::post('/applications', [ApplicationApiController::class, 'store']);
    Route::get('/jobs/{jobPostingId}/pipeline', [ApplicationApiController::class, 'pipeline']);
});
