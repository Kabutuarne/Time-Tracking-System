<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            // 'project_id' => Project::factory(),
            'project_id' => null, // will be set in the seeder
            'due_date' => fake()->optional()->dateTimeBetween('now', '+1 year'),
            'completed_at' => null,
            'completed_by' => null,
            'status' => fake()->randomElement(['in_progress', 'archived', 'completed']),
        ];
    }
}
