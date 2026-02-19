<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        $this->authorize('create', $project);
        return view('tasks.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('create', $project);
        // must authentificate user before allowing to create task | later
        $validator = Validator::make($request->all(), [
            'title' => ['required','string','max:100'],
            'description' => ['nullable','string','max:255'],
            'due_date' => ['nullable','after:now'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Validation failed. Please check the form.');
        }

        $task = new Task($validator->validated());
        $task->project_id = $project->id;
        $task->save();

        return redirect()->route('projects.show', $task->project)->with('success', 'Task succesfully created!');
    }
    public function archived(Project $project, Task $task)
    {
        // abort_unless($task->project_id === $project->id, 404);
        $this->authorize('softDelete', $task);
        $task->status = 'archived';
        $task->save();

        return redirect()->back()->with('success', 'Task succesfully archived!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Task $task)
    {
        $this->authorize('view', $project);
        // abort_unless($task->project_id === $project->id, 404);
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
        $this->authorize('update', $task);
        // abort_unless($task->project_id === $project->id, 404);
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
        $this->authorize('update', $task);

        $validator = Validator::make($request->all(), [
            'title' => ['required','string','max:100'],
            'description' => ['nullable','string','max:255'],
            'due_date' => ['nullable','after:now'],
            'status' => ['required','in:in_progress,completed,archived'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->with('error', 'Data validation failed. Please check the form.');
        }

        $task->update($validator->validated());

        return redirect()
            ->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Task succesfully updated!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return back()->with('success', 'Task succesfully deleted!');
    }
}
