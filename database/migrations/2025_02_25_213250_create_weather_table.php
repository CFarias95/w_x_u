<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('number');
            $table->string('name');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->boolean('is_daytime');
            $table->integer('temperature');
            $table->string('temperature_unit');
            $table->string('temperature_trend')->nullable();
            $table->string('probability_of_precipitation');
            $table->string('wind_speed');
            $table->string('wind_direction');
            $table->string('icon');
            $table->string('short_forecast');
            $table->text('detailed_forecast');
            $table->timestamp('recorded_at');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('time_zone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather');
    }
};
