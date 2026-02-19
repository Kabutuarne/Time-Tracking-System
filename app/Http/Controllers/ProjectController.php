<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::query()
            ->with('user')
            ->withCount([
                'users',
                'tasks',
                'entries',
            ])
            ->where('is_public', '=', '1')
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {  
    $this->authorize('create', Project::class);
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate inputs
        $this->authorize('create', Project::class);

        $validator = Validator::make($request->all(), [
            'title' => ['required','string','max:100'],
            'description' => ['nullable','string','max:255'],
            'is_public' => ['required','boolean'],
            'users' => ['nullable','array'],
            'users.*' => ['exists:users,id'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Validation failed. Please check the form.');
        }

        $validated = $validator->validated();

        // create the project
        $project = Project::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_public' => $validated['is_public'],
            'user_id' => Auth::user()->id,
        ]);

        // attach selected users (if any)
        if (!empty($validated['users'])) {
            $project->users()->attach($validated['users'], ['role' => 'member']);
        }

        // Redirect
        return redirect()->route('projects.show', $project)
            ->with('success', 'Project successfully created!');
    }

    /**
     * Display the specified resource.
     */


    public function show(Project $project)
    {
        $this->authorize('view', $project);

        // Pre-load user's projects with pivot data to avoid N+1 queries in policies
        $user = Auth::user();
        if ($user) {
            $user->load('projects');
        }

        // Preload users
        $project->load('user', 'users');

        // Get all statistics
        $userStats = $this->getUserStats($project);
        $projectStats = $this->getProjectStats($project);
        $weeklyUserStats = $this->getWeeklyUserStats($project);
        $taskStatusStats = $this->getTaskStatusStats($project);
        $taskTimeStats = $this->getTaskTimeStats($project);

        // Entries and tasks pagination are handled by Livewire
        $entries = $project->entries()
            ->latest()
            ->with(['user', 'task', 'project']);

        $tasks = $project->tasks()
            ->latest()
            ->with('project');

        return view(
            'projects.show',
            compact(
                'project',
                'userStats',
                'entries',
                'tasks',
                'projectStats',
                'weeklyUserStats',
                'taskStatusStats',
                'taskTimeStats'
            )
        );
    }
    /**
     * Get project statistics for a specific week (API endpoint).
     */
    public function statistics(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        // Parse week parameter
        $weekStart = $request->query('week_start');
        if ($weekStart) {
            $weekStart = Carbon::createFromFormat('Y-m-d', $weekStart)->startOfDay();
        } else {
            $weekStart = now()->startOfWeek();
        }

        $weekEnd = $weekStart->copy()->endOfWeek();
        $previousWeekStart = $weekStart->copy()->subWeek()->startOfWeek();
        $nextWeekStart = $weekStart->copy()->addWeek()->startOfWeek();

        // Get all statistics using helper methods
        $taskStatusStats = $this->getTaskStatusStats($project);
        $taskTimeStats = $this->getTaskTimeStats($project);
        $weeklyUserActivity = $this->getWeeklyUserActivity($project, $weekStart, $weekEnd);
        $dailyActivityBreakdown = $this->getDailyActivityBreakdown($project, $weekStart, $weekEnd);
        $weeklyStats = $this->getWeeklyStats($project, $weekStart, $weekEnd);

        return response()->json([
            'taskStatusStats' => $taskStatusStats,
            'taskTimeStats' => $taskTimeStats,
            'weeklyUserActivity' => $weeklyUserActivity,
            'dailyActivityBreakdown' => $dailyActivityBreakdown,
            'weeklyStats' => $weeklyStats,
            'weekStart' => $weekStart->format('Y-m-d'),
            'weekEnd' => $weekEnd->format('Y-m-d'),
            'previousWeekStart' => $previousWeekStart->format('Y-m-d'),
            'nextWeekStart' => $nextWeekStart->format('Y-m-d'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $this->authorize('viewUpdate', $project);
        $userStats = $this->getUserStats($project);
        return view('projects.edit', compact('project', 'userStats'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_public' => ['required', 'in:0,1'],
            'status' => ['required', Rule::in(['on-hold', 'finished', 'active'])]
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Data validation failed. Please check the form.');
        }

        $data = $validator->validated();
        $data['is_public'] = (int) $data['is_public'];
        // stores or updates users(members of the project)
        $selectedUsers = $request->input('users', []);
        if (!empty($selectedUsers)) {
            $project->users()->attach($selectedUsers, ['role' => 'member']);
        }

        $project->update($data);

        return redirect()
            ->route('projects.show', $project)->with('success', 'Project updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('projects.index')
            ->with('success', 'Project successfully deleted!');
    }
    /**
     * Archive a project by changing its status
     */
    public function archive(Project $project)
    {
        $this->authorize('softDelete', $project);
        $project->status = 'archived';
        $project->save();
        return redirect()->back()
            ->with('success', 'Project successfully archived!');
    }
    /**
     * Restore an archived project
     */
    public function restore(Project $project)
    {
        $this->authorize('restore', $project);
        $project->status = 'active';
        $project->save();
        return redirect()->back()
            ->with('success', 'Project successfully restored!');
    }
    /**
     * Export weekly statistics as CSV
     */
    public function export(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        $weekStart = $request->week_start
            ? Carbon::createFromFormat('Y-m-d', $request->week_start)->startOfDay()
            : now()->startOfWeek();

        $weekEnd = $weekStart->copy()->endOfWeek();

        return response()->streamDownload(function () use ($project, $weekStart, $weekEnd) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Date',
                'User',
                'Task',
                'Minutes',
                'Hours'
            ]);

            Entry::query()
                ->join('tasks', 'tasks.id', '=', 'entries.task_id')
                ->join('users', 'users.id', '=', 'entries.user_id')
                ->where('tasks.project_id', $project->id)
                ->whereBetween('entries.created_at', [$weekStart, $weekEnd])
                ->select(
                    'entries.created_at',
                    DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
                    'tasks.title',
                    'entries.minutes'
                )
                ->orderBy('entries.created_at')
                ->chunk(500, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            Carbon::parse($row->created_at)->format('Y-m-d'),
                            $row->name,
                            $row->title,
                            $row->minutes,
                            round($row->minutes / 60, 2),
                        ]);
                    }
                });

            fclose($handle);

        }, "weekly_statistics_{$weekStart->format('Y_m_d')}.csv");
    }

    /**
     * Get user statistics for a project (entry count and total minutes per user).
     */
    private function getUserStats(Project $project)
    {
        return Entry::query()
            ->join('tasks', 'tasks.id', '=', 'entries.task_id')
            ->where('tasks.project_id', $project->id)
            ->select(
                'entries.user_id',
                DB::raw('COUNT(entries.id) as entry_count'),
                DB::raw('SUM(entries.minutes) as total_minutes')
            )
            ->groupBy('entries.user_id')
            ->get()
            ->keyBy('user_id');
    }

    /**
     * Get overall project statistics (task count, total minutes, entry count).
     */
    private function getProjectStats(Project $project)
    {
        return Project::query()
            ->join('tasks', 'tasks.project_id', '=', 'projects.id')
            ->leftJoin('entries', 'entries.task_id', '=', 'tasks.id')
            ->where('projects.id', $project->id)
            ->select(
                'projects.id',
                DB::raw('COUNT(DISTINCT tasks.id) as total_task_count'),
                DB::raw('COALESCE(SUM(entries.minutes), 0) as total_minutes'),
                DB::raw('COUNT(entries.id) as total_entry_count')
            )
            ->groupBy('projects.id')
            ->get()
            ->keyBy('id');
    }

    /**
     * Get weekly user statistics including effort and entry counts.
     */
    private function getWeeklyUserStats(Project $project)
    {
        return Entry::query()
            ->join('tasks', 'tasks.id', '=', 'entries.task_id')
            ->join('users', 'users.id', '=', 'entries.user_id')
            ->where('tasks.project_id', $project->id)
            ->whereBetween('entries.created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->select(
                'entries.user_id',
                DB::raw('SUM(entries.minutes) as total_minutes'),
                DB::raw('COUNT(entries.id) as entry_count')
            )
            ->groupBy('entries.user_id')
            ->get()
            ->map(function ($stat) {
                $stat->user = User::find($stat->user_id);
                return $stat;
            });
    }

    /**
     * Get task status statistics (excluding archived tasks).
     */
    private function getTaskStatusStats(Project $project)
    {
        return Task::query()
            ->where('project_id', $project->id)
            ->where('status', '!=', 'archived')
            ->select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();
    }

    /**
     * Get total time spent per task.
     */
    private function getTaskTimeStats(Project $project)
    {
        return Task::query()
            ->leftJoin('entries', 'entries.task_id', '=', 'tasks.id')
            ->where('tasks.project_id', $project->id)
            ->select(
                'tasks.title',
                DB::raw('COALESCE(SUM(entries.minutes), 0) as minutes')
            )
            ->groupBy('tasks.id', 'tasks.title')
            ->orderByDesc('minutes')
            ->get();
    }

    /**
     * Get weekly user activity summary for a specific week.
     */
    private function getWeeklyUserActivity(Project $project, $weekStart, $weekEnd)
    {
        return Entry::query()
            ->join('tasks', 'tasks.id', '=', 'entries.task_id')
            ->join('users', 'users.id', '=', 'entries.user_id')
            ->where('tasks.project_id', $project->id)
            ->whereBetween('entries.created_at', [$weekStart, $weekEnd])
            ->select(
                'users.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
                DB::raw('COUNT(entries.id) as entry_count'),
                DB::raw('SUM(entries.minutes) as total_minutes')
            )
            ->groupBy('users.id')
            ->orderByDesc('total_minutes')
            ->get();
    }

    /**
     * Get daily activity breakdown for a week.
     */
    private function getDailyActivityBreakdown(Project $project, $weekStart, $weekEnd)
    {
        return Entry::query()
            ->join('tasks', 'tasks.id', '=', 'entries.task_id')
            ->where('tasks.project_id', $project->id)
            ->whereBetween('entries.created_at', [$weekStart, $weekEnd])
            ->select(
                DB::raw('DATE(entries.created_at) as date'),
                DB::raw('COUNT(entries.id) as entry_count'),
                DB::raw('SUM(entries.minutes) as total_minutes')
            )
            ->groupBy(DB::raw('DATE(entries.created_at)'))
            ->orderBy('date')
            ->get();
    }

    /**
     * Get weekly statistics summary (totals and counts).
     */
    private function getWeeklyStats(Project $project, $weekStart, $weekEnd)
    {
        $entryQuery = Entry::query()
            ->join('tasks', 'tasks.id', '=', 'entries.task_id')
            ->where('tasks.project_id', $project->id)
            ->whereBetween('entries.created_at', [$weekStart, $weekEnd]);

        $tasksCompletedThisWeek = Task::query()
            ->where('project_id', $project->id)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$weekStart, $weekEnd])
            ->count();

        return [
            'total_entries' => (clone $entryQuery)->count(),
            'total_minutes' => (clone $entryQuery)->sum('entries.minutes') ?? 0,
            'total_users' => (clone $entryQuery)->distinct('entries.user_id')->count('entries.user_id'),
            'tasks_completed' => $tasksCompletedThisWeek,
        ];
    }
}