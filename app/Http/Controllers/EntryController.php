<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Entry;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EntryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project, Task $task)
    {
        $this->authorize('createEntry', $task);
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
        $this->authorize('createEntry', $task);
        
        $validator = Validator::make($request->all(), [
            'work_date' => ['required', 'date'],
            'minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'description' => ['required', 'string', 'max:255'],
            'mark_complete' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Validation failed. Please check the form.');
        }

        $validated = $validator->validated();

        Entry::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'work_date' => $validated['work_date'],
            'minutes' => $validated['minutes'],
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->boolean('mark_complete')) {
            $task->markComplete(Auth::id());
        }
        return redirect()
            ->route('projects.show', $project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Task $task, Entry $entry)
    {
        $this->authorize('update', $entry);
        return view('entries.edit', compact( 'project','task','entry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, Task $task, Entry $entry)
    {
        $this->authorize('update', $entry);
        
        $validator = Validator::make($request->all(), [
            'work_date' => ['required', 'date'],
            'minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Data validation failed. Please check the form.');
        }

        $validated = $validator->validated();
        $entry->update($validated);
        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Entry successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Task $task, Entry $entry)
    {
        $this->authorize('delete', $entry);
        $entry->delete();
        return back()->with('success', 'Entry successfully deleted!');
    }

}
