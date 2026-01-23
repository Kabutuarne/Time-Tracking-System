<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //fixed n+1 problem and optimized entries count query
        $projects = Project::with('user')
            ->withCount(['users', 'tasks'])
            // selectRaw adds a subquery to count entries
            ->selectRaw('projects.*, (SELECT count(*) FROM entries WHERE task_id IN (SELECT id FROM tasks WHERE tasks.project_id = projects.id)) AS entries_count')
            ->get();
        
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     */


    public function show(Project $project)
    {
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
                DB::raw('COALESCE(SUM(entries.minutes), 0) as total_minutes'), // total minutes from all entries
                DB::raw('COUNT(entries.id) as total_entry_count') // total entry count
            )
            ->groupBy('projects.id')
            ->get()
            ->keyBy('id');

        // entry pagination
        $entries = $project->entries()
            ->with(['user', 'task', 'project'])
            ->paginate(5, ['*'], 'entries_page');
        // task pagination
        $tasks = $project->tasks()
            ->with('project')
            ->paginate(5, ['*'], 'tasks_page');

        return view('projects.show', compact('project', 'userStats', 'entries', 'tasks', 'projectStats'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('projects.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
