<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'description' => fake()->paragraph(),
            // 'user_id' => User::factory(),
            'user_id' => null, // will be set in the seeder
            'status' => fake()->randomElement(['active', 'finished', 'on-hold', 'archived']),
            'is_public' => fake()->boolean(30), // 30% to be true
        ];
    }
}
