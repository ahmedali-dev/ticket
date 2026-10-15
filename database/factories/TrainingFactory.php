<?php

namespace Database\Factories;

use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->randomNumber(),
            'title' => fake()->sentence(4),
            'order' => fake()->numberBetween(1, 100),
            'user_id' => User::factory(),
            'active' => true,
            'media_id' => null,
        ];
    }
}
