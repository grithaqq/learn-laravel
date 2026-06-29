<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('logout', [AuthController::class, 'logout']);
});
