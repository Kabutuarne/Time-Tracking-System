<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entry;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Get owned projects
        $ownedProjects = Project::query()
            ->where('user_id', $user->id)
            ->withCount(['tasks', 'entries'])
            ->latest()
            ->get();

        // Get projects the user is a part of
        $memberProjects = Project::query()
            ->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->where('user_id', '!=', $user->id)
            ->withCount(['tasks', 'entries'])
            ->latest()
            ->get();

        // Get statistics data for the past week
        $weeklyWork = $this->getUserWeeklyStats($user);

        return view('users.show', [
            'user'           => $user,
            'ownedProjects'  => $ownedProjects,
            'memberProjects' => $memberProjects,
            'weeklyWork'     => $weeklyWork,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('users.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        abort_unless(Auth::user()->id === $user->id, 403);

        $validated = $request->validate([
            'username' => ['required','string','max:100', Rule::unique(User::class)->ignore($user)],
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
        ]);

        Auth::user()->update($validated);

        return redirect()->route('users.show', $user)->with('success', 'Profile successfully updated!');
    }
    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Request $request, User $user)
    {
        abort_unless(Auth::user()->id === $user->id, 403);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('projects.index')->with('success', 'Profile succesfully deleted!');
    }

    // Project management functions
    public function attachToProject(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($request->user_id);

        // Check if user is already in project
        if ($project->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this project');
        }

        $project->users()->attach($user->id, ['role' => 'member']);

        return back()->with('success', 'User successfully attached to project!');
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

        return back()->with('success', 'Role successfully changed!');
    }

    public function detachFromProject(Project $project, User $user)
    {
        $project->users()->detach($user->id);
        return back()->with('success', 'User successfully removed from project!');
    }

    /**
     * Search for users by username or email.
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        return User::query()
            ->where('username', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'username', 'email']);
    }

    /**
     * Get user's weekly work statistics by project.
     */
    private function getUserWeeklyStats(User $user)
    {
        $start = now()->subDays(6)->startOfDay();
        $end = now()->endOfDay();

        return Entry::query()
            ->join('tasks', 'tasks.id', '=', 'entries.task_id')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->where('entries.user_id', '=', $user->id)
            ->whereBetween(
                DB::raw('DATE(entries.work_date)'),
                [$start->toDateString(), $end->toDateString()]
            )
            ->select(
                DB::raw('DATE(entries.work_date) as work_date'),
                'projects.id as project_id',
                'projects.title as project_title',
                DB::raw('SUM(entries.minutes) as total_minutes')
            )
            ->groupBy(
                DB::raw('DATE(entries.work_date)'),
                'projects.id',
                'projects.title'
            )
            ->orderBy('work_date')
            ->get();
    }
}
