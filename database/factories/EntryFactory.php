<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entry>
 */
class EntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'task_id' => Task::factory(),
            // 'user_id' => User::factory(),
            'task_id' => null, // will be set in the seeder
            'user_id' => null,
            'work_date' => fake()->date(),
            'minutes' => fake()->numberBetween(15, 480), // 15min to 8h
            'description' => fake()->sentence(),
        ];
    }
}
