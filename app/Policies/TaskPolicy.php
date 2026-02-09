<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     return false;
    // }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(?User $user, Project $project): bool
    // {
    //     if ($project->is_public) {
    //         return true;
    //     }


    //     return $user->id === $project->user_id
    //         || $user->isInProject($project);    
    // }

    /**
     * Determine whether the user can create models.
     */
    public function modifyTask(User $user, Task $task): bool
    {
        return $user->atLeastRoleInProject($task->project, 'manager')
        && $task->project->status == 'active'; //can only add new tasks to active projects
    }

    public function createEntry(User $user, Task $task): bool
    {
        return $user->atLeastRoleInProject($task->project, 'member')
        && $task->status == 'in_progress';
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->atLeastRoleInProject($task->project, 'manager')
        && $task->project->status == 'active';
    }

    
    /**
     * Determine if user can soft delete task (set to archived)
     */
    public function softDelete(User $user, Task $task): bool
    {
        return $user->atLeastRoleInProject($task->project, 'manager')
        && $task->project->status == 'active';
    }
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id; //can only owner
    }
}
