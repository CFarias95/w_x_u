@extends('layouts.app')

@section('content')
    <div x-data="{ openModal: false, currentWeather: null }" class="relative min-h-screen bg-gradient-to-b from-blue-100 to-white">

        <div class="absolute inset-0 bg-[url('/images/clouds.jpg')] bg-cover bg-center opacity-20"></div>

        <!-- Título principal -->
        <div class="relative z-10 container mx-auto px-6 py-12">

            <h1 class="text-5xl font-extrabold text-center text-gray-900 mb-10">
                🌎 Monitor de Climas
            </h1>

            <!-- Estado de carga inicial -->
            @if ($loading)
                <div
                    class="flex flex-col items-center justify-center p-6 bg-yellow-100 border border-yellow-300 rounded-lg shadow-md">
                    <p class="text-lg text-yellow-800 font-semibold">
                        🔄 Cargando datos... Esto puede tardar unos minutos.
                    </p>
                    <div class="mt-3 animate-spin text-3xl">⏳</div>
                </div>
            @else
                <!-- Grid de usuarios y clima -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($users as $user)
                        <div @click="openModal = true; fetch('{{ route('weather.current', $user['id']) }}')
                              .then(response => response.json())
                              .then(data => currentWeather = data.data)"
                            class="cursor-pointer bg-white rounded-2xl shadow-lg p-6 hover:shadow-2xl transition-all flex items-center gap-5 border border-gray-200 hover:border-gray-300">

                            <!-- Ícono del clima -->
                            <div class="w-20 h-20 bg-blue-200 rounded-lg flex items-center justify-center">
                                <img src="{{ $user['weather']->icon }}" alt="Weather Icon" class="w-16 h-16">
                            </div>

                            <!-- Información del usuario y clima -->
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    {{ $user['name'] }}
                                </h3>
                                <div class="flex items-center gap-3 mt-1">
                                    <p class="text-4xl font-bold text-blue-600">
                                        {{ $user['weather']->temperature }}°{{ $user['weather']->temperature_unit }}
                                    </p>
                                    <p class="text-gray-600 text-lg">
                                        {{ $user['weather']->short_forecast }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between mt-2 text-sm text-gray-500">
                                    <p>📅 Actualizado: {{ $user['weather']->recorded_at->diffForHumans() }}</p>
                                    @if (!$user['is_updated'])
                                        <span
                                            class="px-2 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs font-medium">
                                            ⏳ Desactualizado
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Modal de información detallada -->
                <div x-show="openModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4"
                    style="display: none;">
                    <div class="bg-white rounded-2xl max-w-lg w-full p-8 shadow-xl relative">

                        <!-- Botón de cierre -->
                        <button @click="openModal = false"
                            class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-2xl">
                            &times;
                        </button>
                       
                        <h2 class="text-3xl font-bold text-center mb-4" x-text="currentWeather.user_name"></h2>

                        <template x-if="currentWeather">
                            
                            <!-- Información del clima -->
                            <div class="grid grid-cols-2 gap-4 text-gray-800">
                                <div class="col-span-2 text-center text-lg font-semibold bg-gray-100 p-4 rounded-lg">
                                    <span x-text="currentWeather.conditions"></span>
                                </div>
                                <div class="p-4 text-center bg-blue-100 rounded-lg">
                                    <p class="font-semibold">🌡️ Temperatura</p>
                                    <p class="text-xl font-bold" x-text="`${currentWeather.temperature}°${currentWeather.temperature_unit}`"></p>
                                </div>
                                <div class="p-4 text-center bg-blue-100 rounded-lg">
                                    <p class="font-semibold">💨 Velocidad del viento</p>
                                    <p class="text-xl font-bold"
                                        x-text="`${currentWeather.wind_speed} ${currentWeather.wind_direction}`"></p>
                                </div>
                                <div class="p-4 text-center bg-blue-100 rounded-lg">
                                    <p class="font-semibold">🌧️ Llovera?</p>
                                    <p class="text-xl font-bold" x-text="`${currentWeather.probability_of_precipitation}%`">
                                    </p>
                                </div>
                                <div class="p-4 text-center bg-blue-100 rounded-lg">
                                    <p class="font-semibold">⌚ Horario</p>
                                    <p class="text-sm font-bold" x-text="`${currentWeather.start_time}`">
                                    <p class="text-sm font-bold" x-text="`${currentWeather.end_time}`">
                                    </p>
                                </div>
                                <div class="col-span-2 text-center rounded-lg">
                                    <p class="font-semibold" x-text="`${currentWeather.city}`"></p>
                                    <p class="text-sm font-bold" x-text="`${currentWeather.state}`">
                                    <p class="text-sm font-bold" x-text="`${currentWeather.time_zone}`">
                                    </p>
                                </div>
                                <div class="col-span-2 text-center text-gray-500 text-sm mt-2">
                                    ⏰ <span
                                        x-text="`Actualizado: ${new Date(currentWeather.recorded_at).toLocaleTimeString()}`"></span>
                                </div>
                            </div>
                        </template>

                        <!-- Estado de carga en el modal -->
                        <template x-if="!currentWeather">
                            <div class="text-center py-8">
                                <p class="text-gray-500">🔄 Cargando datos climáticos...</p>
                            </div>
                        </template>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
