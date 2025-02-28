<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WeatherController::class, 'dashboard'])->name('dashboard');
Route::get('/weather/{user}/current', [WeatherController::class, 'getCurrentWeather'])->name('weather.current');
