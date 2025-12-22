<?php

use App\Http\Controllers\Api\TaskApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Task API Routes
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskApiController::class, 'index']);
    Route::post('/', [TaskApiController::class, 'store']);
    Route::get('/{task}', [TaskApiController::class, 'show']);
    Route::put('/{task}', [TaskApiController::class, 'update']);
    Route::delete('/{task}', [TaskApiController::class, 'destroy']);
    Route::patch('/{task}/status', [TaskApiController::class, 'updateStatus']);
});
