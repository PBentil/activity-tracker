<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement([
                'general',
                'sms',
                'logs',
                'monitoring',
                'incident',
                'change',
            ]),
            'activity_date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
        ];
    }
}
