<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    // public function modifyTask(User $user, Task $task): bool
    // {
    //     return $user->atLeastRoleInProject($task->project, 'manager')
    //     && ($task->project->status == 'active' || 'on-hold'); //can only add new tasks to active projects
    // }

    /**
     * Ensure project relationship is loaded to avoid duplicate queries
     */
    protected function ensureProjectLoaded(Task $task): Project
    {
        if (!$task->relationLoaded('project')) {
            $task->load('project');
        }
        return $task->project;
    }

    public function createEntry(User $user, Task $task): bool
    {
        $project = $this->ensureProjectLoaded($task);
        
        return $user->atLeastRoleInProject($project, 'member')
        && $task->status === 'in_progress'
        && $project->status === 'active';
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        $project = $this->ensureProjectLoaded($task);
        
        return $user->atLeastRoleInProject($project, 'manager')
        && in_array($project->status, ['active', 'on-hold'], true);
    }
    
    /**
     * Determine if user can soft delete task (set to archived)
     */
    public function softDelete(User $user, Task $task): bool
    {
        $project = $this->ensureProjectLoaded($task);
        
        return $user->atLeastRoleInProject($project, 'manager')
        && in_array($project->status, ['active', 'on-hold'], true);
    }
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        $project = $this->ensureProjectLoaded($task);
        
        return $user->id === $project->user_id
        && in_array($project->status, ['active', 'on-hold'], true);//can only owner
    }
}
