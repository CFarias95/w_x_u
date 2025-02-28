<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Programar el job con condiciones
        $schedule->job(new \App\Jobs\FetchWeatherData)
            ->everyThirtyMinutes() // cda 30 min ACTUALIZA LA INFROMACION()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/scheduler.log'));
    }

    protected function booted() {}

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
