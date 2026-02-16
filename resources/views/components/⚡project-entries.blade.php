<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;

new class extends Component {
    use WithPagination;

    public Project $project;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        return $this->view([
            'entries' => $this->project->entries()
                ->latest()
                ->with(['user', 'task', 'project'])
                ->paginate(5, ['*'], 'entries_page'),
        ]);
    }
};
?>

<div>
    <div class="space-y-4">
        @forelse ($entries as $entry)
            <x-projects.entry-card :entry="$entry" :project="$project" />
        @empty
            <div class="rounded-xl bg-slate-950/40 p-8 text-center ring-1 ring-white/5">
                <i class="fa-solid fa-inbox text-4xl text-slate-600 mb-3"></i>
                <p class="text-slate-400">No entries yet.</p>
            </div>
        @endforelse

        {{ $entries->links('custom-pagination', ['pagename' => 'entries_page']) }}

    </div>
</div>