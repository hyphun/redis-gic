<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedisItemController;
Route::prefix('redis')->group(function () {
    Route::get('/', [RedisItemController::class, 'index']);
    Route::post('/store', [RedisItemController::class, 'store']);
    Route::get('{key}', [RedisItemController::class, 'show']);
    Route::put('{key}', [RedisItemController::class, 'update']);
    Route::delete('{key}', [RedisItemController::class, 'destroy']);
    Route::delete('/', [RedisItemController::class, 'flush']);
});
