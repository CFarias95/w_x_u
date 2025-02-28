<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class WeatherUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public function __construct()
    {
        //
    }

    public function broadcastOn()
    {
        return ['weather-updates'];
    }

    public function broadcastAs()
    {
        return 'weather.updated';
    }
}
