<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    /**
     * Mark the specified task as completed.
     */
    public function complete(Project $project, Task $task)
    {
        abort_unless($task->project_id === $project->id, 404);
        $user = Auth::id();

        $task->completed_at = now();
        $task->completed_by = $user;
        $task->status = 'completed';
        $task->save();

        return redirect()->back()->with('success', 'Task marked as completed.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        //add to which project the task will be added
        return view('tasks.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        
        // must authentificate user before allowing to create task | later
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'due_date' => 'nullable|datetime|after:datetime:now',
        ]);

        $task = new Task($validated);
        $task->project_id = $project->id;
        $task->save();

        return redirect()->route('projects.show', $task->project);
    }
    public function archived(Project $project, Task $task)
    {
        abort_unless($task->project_id === $project->id, 404);

        $task->status = 'archived';
        $task->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Task $task)
    {
        abort_unless($task->project_id === $project->id, 404);
        // eager load project
        $task->load(['project']);
        // get task stats
        $taskStats = $task->entries()
            ->selectRaw('COUNT(id) as total_entry_count, COALESCE(SUM(minutes), 0) as total_minutes')
            ->first();
        // get paginated entries
        $entries = $task->entries()
            ->latest()
            ->paginate(10);

        return view('tasks.show', compact(
            'task',
            'project',
            'taskStats',
            'entries'
        ));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Task $task)
    {
        abort_unless($task->project_id === $project->id, 404);
        // eager load project
        $task->load(['project']);
        // get task stats
        $taskStats = $task->entries()
            ->selectRaw('COUNT(id) as total_entry_count, COALESCE(SUM(minutes), 0) as total_minutes')
            ->first();
        // get paginated entries
        $entries = $task->entries()
            ->latest()
            ->paginate(10);

        return view('tasks.edit', compact(
            'task',
            'project',
            'taskStats',
            'entries'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, Task $task)
    {
        abort_unless($task->project_id === $project->id, 404);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'status' => 'required|in:in_progress,completed,archived',
        ]);

        $task->update($validated);

        return redirect()
            ->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task updated.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Task $task)
    {
        abort_unless($task->project_id === $project->id, 404);
        $task->delete();
        return back()->with('success', 'Entry removed.');
    }
}
