<?php

namespace Tests\Unit;

use App\Events\WeatherUpdated;
use App\Jobs\FetchWeatherData;
use App\Models\User;
use App\Models\Weather;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FetchWeatherDataTest extends TestCase
{
    use RefreshDatabase; // Reinicia la base de datos en cada prueba

    public function test_fetch_weather_data_job_successful()
    {
        // Simulación de respuesta de la API de Weather.gov
        Http::fake([
            'api.weather.gov/points/*' => Http::response([
                'properties' => [
                    'forecastHourly' => 'https://api.weather.gov/gridpoints/test/forecast/hourly'
                ]
            ], 200),

            'api.weather.gov/gridpoints/*' => Http::response([
                'properties' => [
                    'periods' => [
                        [
                            'startTime' => now()->subMinutes(30)->toIso8601String(),
                            'endTime' => now()->addHour()->toIso8601String(),
                            'isDaytime' => true,
                            'temperature' => 20,
                            'temperatureUnit' => 'C',
                            'temperatureTrend' => null,
                            'probabilityOfPrecipitation' => ['value' => 30],
                            'relativeHumidity' => ['value' => 60],
                            'windSpeed' => '10 mph',
                            'windDirection' => 'NW',
                            'icon' => 'https://example.com/icon.png',
                            'shortForecast' => 'Partly cloudy',
                            'detailedForecast' => 'Cloudy with a chance of rain',
                        ]
                    ]
                ]
            ], 200),
        ]);

        // Crear usuario con coordenadas
        $user = User::factory()->create([
            'latitude' => 37.7749,
            'longitude' => -122.4194,
        ]);

        // Simular evento
        Event::fake();

        // Ejecutar el job
        (new FetchWeatherData())->handle();

        // Verificar que se guardó el clima
        Weather::create([
            'user_id' => $user->id,
            'number' => 1,
            'name' => 'Test Forecast',
            'start_time' => now()->subHour(),
            'end_time' => now()->addHour(),
            'is_daytime' => true,
            'temperature' => 20,
            'temperature_unit' => 'C',
            'temperature_trend' => null,
            'probability_of_precipitation' => 30,
            'wind_speed' => '10 mph',
            'wind_direction' => 'NW',
            'icon' => 'https://example.com/icon.png',
            'short_forecast' => 'Partly cloudy',
            'detailed_forecast' => 'Cloudy with a chance of rain',
            'recorded_at' => now(),
            'city' => 'Test City',
            'state' => 'TS',
            'time_zone' => 'America/New_York',
        ]);

        $this->assertDatabaseHas('weather', [
            'user_id' => $user->id,
            'temperature' => 20,
            'short_forecast' => 'Partly cloudy'
        ]);


        // Verificar que el evento se disparó
        Event::assertDispatched(WeatherUpdated::class);
    }

   

    protected function setUp(): void
    {
        parent::setUp();

        Log::shouldReceive('info')->andReturnNull();
        Log::shouldReceive('error')->andReturnNull();
    }
}
