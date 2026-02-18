<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;

new class extends Component {
    use WithPagination;

    public Project $project;

    public string $filter = 'all'; // default: all
    public string $sortOrder = 'latest'; // default: latest

    protected $paginationTheme = 'tailwind';


    public function updatingFilter()
    {
        $this->resetPage('entries_page');
    }

    public function updatingSortOrder()
    {
        $this->resetPage('entries_page');
    }

    public function toggleSort()
    {
        $this->sortOrder = $this->sortOrder === 'latest' ? 'oldest' : 'latest';
        $this->resetPage('entries_page');
    }

    public function render()
    {
        $query = $this->project->entries()
            ->with(['user', 'task', 'project'])
            ->orderBy('created_at', $this->sortOrder === 'latest' ? 'desc' : 'asc');

        if ($this->filter === 'yours') {
            $query->where('user_id', auth()->id());
        }

        return $this->view([
            'entries' => $query->paginate(5, ['*'], 'entries_page'),
        ]);
    }

};
?>

<div>
    <div class="flex flex-wrap gap-2 mb-3">
        {{-- Filter buttons --}}
        <x-forms.sm-filter-button wire:click="$set('filter','all')" :selected="$filter === 'all'">
            All
        </x-forms.sm-filter-button>

        <x-forms.sm-filter-button wire:click="$set('filter','yours')" :selected="$filter === 'yours'">
            Your Entries
        </x-forms.sm-filter-button>

        {{-- Sort toggle --}}
        <x-forms.sm-filter-button :secondary="true" wire:click="toggleSort">
            {{ $sortOrder === 'latest' ? 'Latest' : 'Oldest' }}
        </x-forms.sm-filter-button>
    </div>

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