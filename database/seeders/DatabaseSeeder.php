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
        //User::factory(10)->create();
        $users = User::factory(50)->create();

        // make projects with members 
        Project::factory()
            ->count(5)
            ->make()
            ->each(function ($project) use ($users) {
                $owner = $users->random();
                $project->user_id = $owner->id; // set project owner
                $project->save();

                // exclude owner from potential members
                $members = $users->where('id', '!=', $owner->id)->random(rand(2, min(5, $users->count() - 1)));
                $project->users()->attach($members->pluck('id'));

                //tasks for project
                $tasks = Task::factory(rand(2,10))->make(['project_id' => $project->id]);
                $project->tasks()->saveMany($tasks);

                //entries for each task
                foreach ($tasks as $task){
                    Entry::factory(rand(1,5))
                        ->make([
                            'task_id' => $task->id,
                            'user_id' => $project->users->random()->id,
                        ])->each(fn ($entry) => $entry->save());
                }
            });
            // $tasks = Task::factory()->count(rand(2,10))->make(['project_id' => $project->id]);
    }
}
