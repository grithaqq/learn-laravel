<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DistrictController;

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('logout', [AuthController::class, 'logout']);

    // Province Routes
    Route::prefix('province')->group(function () {
        Route::get('', [ProvinceController::class, 'index']);
        Route::post('', [ProvinceController::class, 'create']);
        Route::get('/{id}', [ProvinceController::class, 'detail']);
        Route::put('/{id}', [ProvinceController::class, 'update']);
        Route::patch('/{id}', [ProvinceController::class, 'patch']);
        Route::delete('/{id}', [ProvinceController::class, 'delete']);
    });

    // City Routes
    Route::prefix('city')->group(function () {
        Route::get('', [CityController::class, 'index']);
        Route::post('', [CityController::class, 'create']);
        Route::get('/{id}', [CityController::class, 'detail']);
        Route::put('/{id}', [CityController::class, 'update']);
        Route::patch('/{id}', [CityController::class, 'patch']);
        Route::delete('/{id}', [CityController::class, 'delete']);
    });

    // District Routes
    Route::prefix('district')->group(function () {
        Route::get('', [DistrictController::class, 'index']);
        Route::post('', [DistrictController::class, 'create']);
        Route::get('/{id}', [DistrictController::class, 'detail']);
        Route::put('/{id}', [DistrictController::class, 'update']);
        Route::patch('/{id}', [DistrictController::class, 'patch']);
        Route::delete('/{id}', [DistrictController::class, 'delete']);
    });
});

