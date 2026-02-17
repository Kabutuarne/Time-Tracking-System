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
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // improved readability
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
        // $this->authorize('create');

        $validated = $request->validate([
            'title' => ['required','string','max:100'],
            'description' => ['nullable','string','max:255'],
            'is_public' => ['required','boolean'],
            'users' => ['nullable','array'],
            'users.*' => ['exists:users,id'],
        ]);

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

        // redirect
        return redirect()->route('projects.show', $project)->with('success', 'Project succesfully created!');
    }

    /**
     * Display the specified resource.
     */


    public function show(Project $project)
    {

        // use of policy
        $this->authorize('view', $project);
        // preload users
        $project->load('user', 'users');

        // preload entry count and total minutes per user
        $userStats = Entry::query()
            ->join('tasks', 'tasks.id', '=', 'entries.task_id')
            ->where('tasks.project_id', $project->id)
            ->select(
                'entries.user_id',
                DB::raw('COUNT(entries.id) as entry_count'), // total entries per user
                DB::raw('SUM(entries.minutes) as total_minutes') // total minutes per user
            )
            ->groupBy('entries.user_id')
            ->get()
            ->keyBy('user_id');

        // preload project stats: total tasks, total minutes, total entries for the project
        $projectStats = Project::query()
            ->join('tasks', 'tasks.project_id', '=', 'projects.id') // join tasks table
            ->leftJoin('entries', 'entries.task_id', '=', 'tasks.id') // left join entries table
            ->where('projects.id', $project->id)
            ->select(
                'projects.id',
                DB::raw('COUNT(DISTINCT tasks.id) as total_task_count'), // total task count
                DB::raw('COALESCE(SUM(entries.minutes), 0) as total_minutes'), // total minutes
                DB::raw('COUNT(entries.id) as total_entry_count') // total entry count
            )
            ->groupBy('projects.id')
            ->get()
            ->keyBy('id');

        // Weekly user stats for "Weekly Effort" and "Entries" tabs
        $weeklyUserStats = Entry::query()
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

        // Task status stats for "Tasks" tab (for donut chart) without archived ones
        $taskStatusStats = Task::query()
            ->where('project_id', $project->id)
            ->where('status', '!=', 'archived')
            ->select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();
            
        // Total time per task (for bar chart)
        $taskTimeStats = Task::query()
            ->leftJoin('entries', 'entries.task_id', '=', 'tasks.id')
            ->where('tasks.project_id', $project->id)
            ->select(
                'tasks.title',
                DB::raw('COALESCE(SUM(entries.minutes), 0) as minutes')
            )
            ->groupBy('tasks.id', 'tasks.title')
            ->orderByDesc('minutes')
            ->get();


        // entry pagination
        $entries = $project->entries()
            ->latest()
            ->with(['user', 'task', 'project']);
            // ->paginate(5, ['*'], 'entries_page');

        // task pagination
        $tasks = $project->tasks()
            ->latest()
            ->with('project');
            // ->paginate(5, ['*'], 'tasks_page');

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
     * Statistics display api
     */
    public function statistics(Request $request, Project $project)
    {
        $this->authorize('view', $project);

        // Get week parameter (format: YYYY-MM-DD for start of week)
        $weekStart = $request->query('week_start');
        if ($weekStart) {
            $weekStart = Carbon::createFromFormat('Y-m-d', $weekStart)->startOfDay();
        } else {
            $weekStart = now()->startOfWeek();
        }
        $weekEnd = $weekStart->copy()->endOfWeek();
        $previousWeekStart = $weekStart->copy()->subWeek()->startOfWeek();
        $nextWeekStart = $weekStart->copy()->addWeek()->startOfWeek();
        
        // Task status stats for donut chart (excluding archived tasks)
        $taskStatusStats = Task::query()
            ->where('project_id', $project->id)
            ->where('status', '!=', 'archived')
            ->select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();
        // Task time stats for bar chart
        $taskTimeStats = Task::query()
            ->leftJoin('entries', 'entries.task_id', '=', 'tasks.id')
            ->where('tasks.project_id', $project->id)
            ->where('tasks.status', '!=', 'archived')
            ->select(
                'tasks.title',
                DB::raw('COALESCE(SUM(entries.minutes), 0) as minutes')
            )
            ->groupBy('tasks.id', 'tasks.title')
            ->orderByDesc('minutes')
            ->get();

        // Weekly summary data
        $weeklyUserActivity = Entry::query()
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

        // Daily breakdown for the week
        $dailyActivityBreakdown = Entry::query()
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

        // Task completions this week
        $tasksCompletedThisWeek = Task::query()
            ->where('project_id', $project->id)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$weekStart, $weekEnd])
            ->count();

        // Overall weekly stats
        $weeklyStats = [
            'total_entries' => Entry::query()
                ->join('tasks', 'tasks.id', '=', 'entries.task_id')
                ->where('tasks.project_id', $project->id)
                ->whereBetween('entries.created_at', [$weekStart, $weekEnd])
                ->count(),
            'total_minutes' => Entry::query()
                ->join('tasks', 'tasks.id', '=', 'entries.task_id')
                ->where('tasks.project_id', $project->id)
                ->whereBetween('entries.created_at', [$weekStart, $weekEnd])
                ->sum('entries.minutes') ?? 0,
            'total_users' => Entry::query()
                ->join('tasks', 'tasks.id', '=', 'entries.task_id')
                ->where('tasks.project_id', $project->id)
                ->whereBetween('entries.created_at', [$weekStart, $weekEnd])
                ->distinct('entries.user_id')
                ->count('entries.user_id'),
            'tasks_completed' => $tasksCompletedThisWeek,
        ];

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
        // Get user stats
        $userStats = Entry::query()
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

        return view('projects.edit', compact('project', 'userStats'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_public' => ['required', 'in:0,1'],
            'status' => ['required', Rule::in(['on-hold', 'finished', 'active'])]
        ]);
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
        return redirect()->route('projects.index')->with('success', 'Project succesfully deleted!');
    }
    public function archive(Project $project)
    {
        $this->authorize('softDelete', $project);
        $project->status = 'archived';
        $project->save();
        return redirect()->back()->with('success', 'Project succesfully archived!');
    }
    public function restore(Project $project)
    {
        $this->authorize('restore', $project);
        $project->status = 'active';
        $project->save();
        return redirect()->back()->with('success', 'Project succesfully restored!');
    }
}