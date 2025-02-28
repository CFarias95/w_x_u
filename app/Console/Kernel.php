<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        Log::info('Scheduling FetchWeatherData job');
        // Programar el job con condiciones
        $schedule->job(new \App\Jobs\FetchWeatherData)
            ->everyThirtyMinutes() // cda 30 min ACTUALIZA LA INFROMACION()
            ->withoutOverlapping();
    }

    protected function booted() {}

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
