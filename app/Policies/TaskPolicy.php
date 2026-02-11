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

    public function createEntry(User $user, Task $task): bool
    {
        return $user->atLeastRoleInProject($task->project, 'member')
        && $task->status === 'in_progress'
        && $task->project->status === 'active';
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->atLeastRoleInProject($task->project, 'manager')
        && in_array($task->project->status, ['active', 'on-hold'], true);
    }
    
    /**
     * Determine if user can soft delete task (set to archived)
     */
    public function softDelete(User $user, Task $task): bool
    {
        return $user->atLeastRoleInProject($task->project, 'manager')
        && in_array($task->project->status, ['active', 'on-hold'], true);
    }
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->project->user_id
        && in_array($task->project->status, ['active', 'on-hold'], true);//can only owner
    }
}
