<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $faker = Faker::create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'latitude' => $faker->latitude(24.396308, 49.384358),
            'longitude' => $faker->longitude(-125.000000, -66.934570),
        ]);

        for ($i = 0; $i < 20; $i++) {
            User::factory()->create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'latitude' => $faker->latitude(24.396308, 49.384358),
                'longitude' => $faker->longitude(-125.000000, -66.934570),
            ]);
        }

    }
}
