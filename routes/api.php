<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/', function () {
        return response()->json([
            'App' => config('app.name'),
            'Laravel' => app()->version(),
            'environment' => config('app.env'),
        ]);
    });

    Route::get('/weather/current', [WeatherController::class, 'current']);
    Route::get('/weather/history', [WeatherController::class, 'history']);
    Route::get('/weather/dashboard', [WeatherController::class, 'dashboard']);
    Route::get('/weather/{user}/current', [WeatherController::class, 'current']);

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
});
