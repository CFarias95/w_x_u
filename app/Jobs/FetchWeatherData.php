<?php

namespace App\Jobs;

use App\Events\WeatherUpdated;
use App\Models\User;
use App\Models\Weather;
use Faker\Factory as Faker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchWeatherData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        Log::info("Fetching weather for {$users->count()} users");

        foreach ($users as $user) {
            try {
                // Obtener datos del punto
                $pointResponse = Http::withHeaders([
                    'User-Agent' => config('app.name').' ('.config('app.url').')',
                ])->get("https://api.weather.gov/points/{$user->latitude},{$user->longitude}");

                Log::info("Fetching weather for user {$user->name}");
                Log::info('Point response: '.$pointResponse->status());
                // Log::info("Point response body: " . $pointResponse->body());

                if ($pointResponse->successful()) {

                    $pointData = $pointResponse->json();
                    Log::info('Point data: '.$pointData['properties']['forecastHourly']);

                    // Obtener pronóstico actual
                    if (isset($pointData['properties']['forecastHourly'])) {
                        $forecastResponse = Http::withHeaders([
                            'User-Agent' => config('app.name').' ('.config('app.url').')',
                        ])->get($pointData['properties']['forecastHourly']);
                    } else {
                        $faker = Faker::create();
                        $user->update([
                            'latitude' => $faker->latitude(24.396308, 49.384358),
                            'longitude' => $faker->longitude(-125.000000, -66.934570),
                        ]);

                        continue;
                    }

                    Log::info('Forecast response: '.$forecastResponse->status());

                    if ($forecastResponse->successful()) {
                        $forecastData = $forecastResponse->json();

                        foreach ($forecastData['properties']['periods'] as $period) {

                            $currentDateTime = now();
                            $startTime = \Carbon\Carbon::parse($period['startTime']);

                            if ($startTime->between($currentDateTime->copy()->subHour(), $currentDateTime, true)) {

                                $period['start_time'] = $period['startTime'];
                                $period['end_time'] = $period['endTime'];
                                $period['is_daytime'] = $period['isDaytime'];
                                $period['temperature'] = $period['temperature'];
                                $period['temperature_unit'] = $period['temperatureUnit'];
                                $period['temperature_trend'] = $period['temperatureTrend'];
                                $period['probability_of_precipitation'] = $period['probabilityOfPrecipitation']['value'] ?? 0;
                                $period['relative_humidity'] = $period['relativeHumidity']['value'] ?? null;
                                $period['wind_speed'] = $period['windSpeed'];
                                $period['wind_direction'] = $period['windDirection'];
                                $period['icon'] = $period['icon'];
                                $period['short_forecast'] = $period['shortForecast'];
                                $period['detailed_forecast'] = $period['detailedForecast'];
                                $period['city'] = $pointData['properties']['relativeLocation']['properties']['city'];
                                $period['state'] = $pointData['properties']['relativeLocation']['properties']['state'];
                                $period['time_zone'] = $pointData['properties']['timeZone'];
                                unset($period['startTime'], $period['dewpoint'], $period['endTime'], $period['isDaytime'], $period['temperatureUnit'], $period['temperatureTrend'], $period['probabilityOfPrecipitation'], $period['dewpoint'], $period['relativeHumidity'], $period['windSpeed'], $period['windDirection'], $period['shortForecast'], $period['detailedForecast']);

                                // Guardar datos en la base de datos
                                Weather::updateOrCreate(
                                    [
                                        'user_id' => $user->id,

                                    ],
                                    $period + [
                                        'recorded_at' => now(),
                                    ]
                                );
                            }
                        }
                    }
                } else {
                    $faker = Faker::create();
                    $user->update([
                        'latitude' => $faker->latitude(24.396308, 49.384358),
                        'longitude' => $faker->longitude(-125.000000, -66.934570),
                    ]);
                }

                broadcast(new WeatherUpdated($user))->toOthers();
                sleep(2);
            } catch (\Exception $e) {
                Log::error("Error fetching weather for user {$user->name}: ".$e->getMessage());
            }
        }

        broadcast(new WeatherUpdated)->toOthers();
    }
}
