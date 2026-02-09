<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Project;
use Symfony\Component\HttpFoundation\Response;

class CheckProjectRole
{
    public function handle(Request $request, Closure $next, string $minRole): Response
    {
        $projectId = $request->route('project') ?? $request->input('project_id');
        
        if (!$projectId) {
            abort(400, 'Project identifier required');
        }
        
        $project = Project::findOrFail($projectId);
        
        if (!$request->user()->atLeastRoleInProject($project, $minRole)) {
            abort(403, 'Insufficient permissions for this project');
        }
        
        return $next($request);
    }
}