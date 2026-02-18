<?php

namespace App\Traits;

use App\Models\Project;

trait HasProjectRoles
{
    /**
     * cache for role lookups
     */
    protected array $roleCache = [];

    /**
     * Get user's role in specific project
     */
    public function roleInProject(Project $project): ?string
    {
        // return cached role if available
        if (isset($this->roleCache[$project->id])) {
            return $this->roleCache[$project->id];
        }
        
        // Check if user is project owner
        if ($this->id === $project->user_id) {
            return $this->roleCache[$project->id] = 'owner';
        }

        // check if user is a menber through the pivot table
        // Try to use already loaded relationships first
        if ($this->relationLoaded('projects')) {
            $projectUser = $this->projects
                ->where('id', $project->id)
                ->first();
            
            if ($projectUser) {
                return $this->roleCache[$project->id] = $projectUser->pivot->role;
            }
        } else {
            // Only query if relationships aren't pre-loaded
            $projectUser = $this->projects()
                ->wherePivot('project_id', $project->id)
                ->first();
                
            if ($projectUser) {
                return $this->roleCache[$project->id] = $projectUser->pivot->role;
            }
        }
        
        return $this->roleCache[$project->id] = null;
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