<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Weather extends Model
{
    protected $fillable = [
        'user_id',
        'number',
        'name',
        'start_time',
        'end_time',
        'is_daytime',
        'temperature',
        'temperature_unit',
        'temperature_trend',
        'probability_of_precipitation',
        'wind_speed',
        'wind_direction',
        'icon',
        'short_forecast',
        'detailed_forecast',
        'recorded_at',
        'city',
        'state',
        'time_zone',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function isRecent()
    {
        return $this->recorded_at->diffInHours(now()) < 1;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
