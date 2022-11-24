<?php

use App\Http\Controllers\Api\SampleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/samples', [SampleController::class, 'index']);
    Route::get('/samples/search', [SampleController::class, 'search']);
    Route::get('/samples/{sample}', [SampleController::class, 'show']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
});
