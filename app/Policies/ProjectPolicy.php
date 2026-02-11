<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{  
    /**
     * Get user's role in project
     */
    public function getUserRole(User $user, Project $project): ?string
    {
        $projectUser = $user->projects()
            ->wherePivot('project_id', $project->id)
            ->first();
            
        return $projectUser ? $projectUser->pivot->role : null;
    }
    
    /**
     * Determine if user can view project
     */
    public function view(?User $user, Project $project): bool
    {
        // Project is public OR user is member/manager/owner
        // dd($user);
        if($user == null)
            return $project->is_public;
        else
            return
                $project->is_public ||
                $user->id === $project->user_id || // owner
                $user->projects()->where('project_id', $project->id)->exists();
    }
    
    /**
     * Determine if user can update project
     */
    public function update(User $user, Project $project): bool
    {
        return $user->atLeastRoleInProject($project, 'manager');
    }
    
    /**
     * Determine if user can manage project members
     */
    public function manageMembers(User $user, Project $project): bool
    {
        return $this->update($user, $project); // same permissions as update
    }
    /**
     * Determine if user can delete project
     */
    public function softDelete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id; // only owner
    }
    public function hardDelete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id && $project->status == 'archived';
    }

    public function create(?User $user): bool //anyone logged in can create a project
    {
        return $user != null;
    }
}