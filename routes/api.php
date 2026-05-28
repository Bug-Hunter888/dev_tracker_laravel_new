<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public: exchange credentials for a Sanctum token
Route::post('/login', [AuthController::class, 'login']);

// Protected: require Bearer token  (Authorization: Bearer <token>)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', fn (Request $request) => $request->user()->only('id', 'name', 'email'));

    Route::get('/tasks',            [TaskApiController::class, 'index']);
    Route::post('/tasks',           [TaskApiController::class, 'store']);
    Route::get('/tasks/{task}',     [TaskApiController::class, 'show']);
    Route::patch('/tasks/{task}',   [TaskApiController::class, 'update']);
    Route::delete('/tasks/{task}',  [TaskApiController::class, 'destroy']);
});
