<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Entry;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project, Task $task)
    {
        abort_unless($task->project_id === $project->id, 404);
        return view('entries.create', [
        'project' => $project,    
        'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Project $project, Task $task, Request $request)
    {
    
        $validated = $request->validate([
            'work_date' => ['required', 'date'],
            'minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'description' => ['required', 'string', 'max:255'],
            'mark_complete' => ['nullable', 'boolean'],
        ]);

        Entry::create([
            'task_id' => $task->id,
            // 'user_id' => auth()->id,
            'user_id' => 1, // for now just 1
            'work_date' => $validated['work_date'],
            'minutes' => $validated['minutes'],
            'description' => $validated['description'] ?? null,
        ]);
        if ($request->boolean('mark_complete')) {
            // $task->markComplete(Auth::id());
            $task->markComplete(1);
        }
        return redirect()
            ->route('projects.show', $project);
    }

    /**
     * Display the specified resource.
     */
    public function show(Entry $entry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Task $task, Entry $entry)
    {
        return view('entries.edit', compact( 'project','task','entry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, Task $task, Entry $entry)
    {
        $validated = $request->validate([
            'work_date' => ['required', 'date'],
            'minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'description' => ['required', 'string', 'max:255'],
        ]);
        $entry->update($validated);
        return redirect()
            ->route('projects.show', $project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Task $task, Entry $entry)
    {
        abort_unless(
    $project->id === $task->project_id && $task->id === $entry->task_id,
    404
        );

        $entry->delete();
        return back();
    }

}
