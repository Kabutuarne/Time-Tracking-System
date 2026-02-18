<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Entry;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create many users
        $users = User::factory(100)->create();

        // Create many projects with members
        Project::factory()
            ->count(30)
            ->make()
            ->each(function ($project) use ($users) {
                $owner = $users->random();
                $project->user_id = $owner->id; // set project owner
                $project->save();

                // Include owner and other random members
                $otherMembers = $users->where('id', '!=', $owner->id)->random(rand(3, min(8, $users->count() - 1)));
                $allMembers = $otherMembers->push($owner)->pluck('id');
                $project->users()->attach($allMembers);

                // Create a lot of tasks for project (made / completed before 3 weeks ago)
                $tasks = Task::factory(rand(10, 30))
                    ->make([
                        'project_id' => $project->id,
                    ])
                    ->each(function ($task) {
                        // Set created_at to 3-8 weeks ago
                        $daysAgo = rand(21, 56);
                        $task->created_at = now()->subDays($daysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                        $task->updated_at = $task->created_at->copy()->addHours(rand(1, 24));
                        
                        // Set completed_at to before 3 weeks ago, completed_by to a random project member
                        if (rand(0, 1)) {
                            $completedDaysAgo = rand(1, 20); // before 3 weeks ago
                            $task->completed_at = now()->subDays($completedDaysAgo)->subHours(rand(0, 23))->subMinutes(rand(0, 59));
                            $task->completed_by = $task->project->users->random()->id;
                            $task->status = 'completed';
                        }
                        $task->save();
                    });

                // Create many entries for each task
                foreach ($tasks as $task) {
                    Entry::factory(rand(10, 30))
                        ->make([
                            'task_id' => $task->id,
                            'user_id' => $task->project->users->random()->id,
                            'minutes' => rand(5, 480), // duration in minutes (5 to 480)
                        ])
                        ->each(function ($entry) {
                            // Set work_date to before 3 weeks ago
                            $daysAgo = rand(1, 56);
                            $workDate = now()->subDays($daysAgo)->startOfDay();
                            $entry->work_date = $workDate->toDateString();
                            
                            // Set created_at and updated_at to same day
                            $entry->created_at = $workDate->copy()->addHours(rand(8, 18))->addMinutes(rand(0, 59));
                            $entry->updated_at = $entry->created_at->copy()->addHours(rand(0, 2));
                            $entry->save();
                        });
                }
            });
    }
}
