<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;

new class extends Component {
    use WithPagination;

    public Project $project;

    public string $statusFilter = 'all'; // default: all excluding archived

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatingStatusFilter()
    {
        $this->resetPage('tasks_page');
    }

    public function render()
    {
        $query = $this->project->tasks()->latest();

        // Filter logic
        if ($this->statusFilter === 'all') {
            // All excluding archived
            $query->where('status', '!=', 'archived');
        } elseif ($this->statusFilter === 'archived') {
            // Only archived tasks
            $query->where('status', 'archived');
        } else {
            // Other statuses: in_progress, completed
            $query->where('status', $this->statusFilter);
        }

        return $this->view([
            'tasks' => $query->paginate(5, ['*'], 'tasks_page'),
        ]);
    }
};
?>

<div>
    <div class="flex flex-wrap gap-2 mb-3">
        {{-- All tasks, besies archived --}}
        <x-forms.sm-filter-button wire:click="$set('statusFilter','all')"
            :selected="$statusFilter === 'all'">All</x-forms.sm-filter-button>

        {{-- status filters --}}
        <x-forms.sm-filter-button wire:click="$set('statusFilter','in_progress')"
            :selected="$statusFilter === 'in_progress'">In Progress</x-forms.sm-filter-button>
        <x-forms.sm-filter-button wire:click="$set('statusFilter','completed')"
            :selected="$statusFilter === 'completed'">Completed</x-forms.sm-filter-button>

        {{-- Archived toggle --}}
        @can('viewArchivedTasks', $project)
            <x-forms.sm-filter-button wire:click="$set('statusFilter','archived')"
                :selected="$statusFilter === 'archived'">Archived</x-forms.sm-filter-button>
        @endcan
    </div>

    <div class="space-y-3">
        @forelse ($tasks as $task)
            <x-projects.task-card :task="$task" :project="$project" />
        @empty
            <div class="rounded-xl bg-slate-950/40 p-6 text-center ring-1 ring-white/5">
                <i class="fa-solid fa-tasks text-3xl text-slate-600 mb-2"></i>
                <p class="text-slate-400 text-sm">No tasks yet.</p>
            </div>
        @endforelse

        {{ $tasks->links('custom-pagination', ['pagename' => 'tasks_page']) }}
    </div>
</div>