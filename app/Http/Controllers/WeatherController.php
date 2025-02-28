<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Weather;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    public function dashboard()
    {
        $users = Cache::remember('dashboard-users', 300, function () {

            return User::with(['latestWeather'])
                ->has('latestWeather')
                ->orderBy('name')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'weather' => $user->latestWeather,
                        'is_updated' => $user->latestWeather->isRecent(),
                    ];
                });
        });

        return view('dashboard', [
            'users' => $users,
            'loading' => Weather::count() === 0,
        ]);

    }

    public function getCurrentWeather(User $user)
    {
        $weather = Cache::remember("user-{$user->id}-weather", 300, function () use ($user) {
            return $user->weather()
                ->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, NOW(), start_time))')
                ->latest()
                ->first()
                ->setAttribute('user_name', $user->name);
        });

        return response()->json([
            'success' => (bool) $weather,
            'data' => $weather,
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $weather = $user->weather()->orderBy('recorded_at', 'desc')->get();

        return response()->json([
            'data' => $weather,
        ]);
    }
}
