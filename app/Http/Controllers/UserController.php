<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    // Project management functions
    public function attachToProject(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if user is already in project
        if ($project->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this project');
        }

        $project->users()->attach($user->id, ['role' => 'member']);

        return back()->with('success', 'User added successfully');
    }

    public function updateProjectRole(Request $request, Project $project, User $user) 
    {
        $request->validate([
            'role' => ['required', Rule::in(['member', 'manager'])],
        ]);

        $project->users()->updateExistingPivot(
            $user->id,
            ['role' => $request->role]
        );

        return back();
    }

    public function detachFromProject(Project $project, User $user)
    {
        $project->users()->detach($user->id);
        return back();
    }
}