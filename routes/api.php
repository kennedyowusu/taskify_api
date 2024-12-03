<?php

use App\Http\Controllers\api\v1\AuthenticationController;
use App\Http\Controllers\api\v1\TaskifyController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthenticationController::class, 'registerUser']);
Route::post('/login', [AuthenticationController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthenticationController::class, 'logoutUser']);

    Route::get('/tasks', [TaskifyController::class, 'index']);
    Route::post('/tasks', [TaskifyController::class, 'store']);
    Route::get('/tasks/{taskify}', [TaskifyController::class, 'show']);
    Route::patch('/tasks/{taskify}', [TaskifyController::class, 'update']);
    Route::delete('/tasks/{taskify}', [TaskifyController::class, 'destroy']);

    Route::patch('/tasks/{taskify}/complete', [TaskifyController::class, 'complete']);

});
