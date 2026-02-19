<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Entry;
use App\Models\Project;

class EntryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Project $project): bool
    {
        return $project->is_public
            || $user->id === $project->user_id
            || $user->isInProject($project);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Entry $entry): bool
    {
        $project = $entry->project;

        return $project->is_public
            || $user->id === $project->user_id
            || $user->isInProject($project);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Entry $entry): bool
    {
        // only the creator can update their entry
        return $entry->user_id === $user->id &&
        $entry->project->status === 'active';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Entry $entry): bool
    {
        return ($entry->user_id === $user->id // the creator or project manager or owner
        || $user->atLeastRoleInProject($entry->project, 'manager')) &&
        $entry->project->status === 'active';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Entry $entry): bool
    {
        return false;
    }
}
