<?php

namespace App\Traits;

use App\Models\Project;

trait HasProjectRoles
{
    /**
     * Get user's role in specific project
     */
    public function roleInProject(Project $project): ?string
    {
        $projectUser = $this->projects()
            ->wherePivot('project_id', $project->id)
            ->first();
            
        if ($projectUser) {
            return $projectUser->pivot->role;
        }
        
        // Check if user is project owner
        if ($this->id === $project->user_id) {
            return 'owner';
        }
        
        return null;
    }
    
    /**
     * Check if user has specific role in project
     */
    public function hasRoleInProject(Project $project, string $role): bool
    {
        $currentRole = $this->roleInProject($project);
        
        if ($role === 'owner') {
            return $currentRole === 'owner';
        }
        
        // Owners can do anything
        if ($currentRole === 'owner') {
            return true;
        }
        
        return $currentRole === $role;
    }
    
    /**
     * Check if user has at least a specific role in project
     */
    public function atLeastRoleInProject(Project $project, string $minRole): bool
    {
        $roleHierarchy = [
            'member' => 1,
            'manager' => 2,
            'owner' => 3,
        ];
        
        $currentRole = $this->roleInProject($project);
        
        if (!$currentRole) return false;
        
        return $roleHierarchy[$currentRole] >= $roleHierarchy[$minRole];
    }
    /*
    * Check if user is in the project
   */
    public function isInProject($project): bool
    {
        if($this->roleInProject($project) == null)
            return false;
            else
            return true;
    }
}