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
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Project $project): bool
    {
        if ($project->is_public) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->id === $project->user_id
            || $user->projects()->where('project_id', $project->id)->exists();    
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        return $user->atLeastRoleInProject($project, 'manager');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->atLeastRoleInProject($project, 'manager');
    }

    
    /**
     * Determine if user can soft delete task (set to archived)
     */
    public function softDelete(User $user, Project $project): bool
    {
        return $user->atLeastRoleInProject($project, 'manager'); // only owner
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->atLeastRoleInProject($project, 'manager');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id; //can only owner
    }
}
