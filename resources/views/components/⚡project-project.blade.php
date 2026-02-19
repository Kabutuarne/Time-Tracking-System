<?php

use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $sortOrder = 'latest'; // default: latest

    protected $paginationTheme = 'tailwind';

    public function updatingSortOrder()
    {
        $this->resetPage('projects_page');
    }

    public function toggleSort()
    {
        $this->sortOrder = $this->sortOrder === 'latest' ? 'oldest' : 'latest';
        $this->resetPage('projects_page');
    }

    public function render()
    {
        $query = \App\Models\Project::query()
            ->with('user')
            ->withCount([
                'users',
                'tasks',
                'entries',
            ])
            ->where('is_public', '=', '1')
            ->orderBy('created_at', $this->sortOrder === 'latest' ? 'desc' : 'asc');

        return $this->view([
            'projects' => $query->paginate(9, ['*'], 'projects_page'),
        ]);
    }

};
?>

<div>
    <div class="flex flex-wrap gap-2 mb-6">
        {{-- Sort toggle --}}
        <x-forms.sm-filter-button :secondary="false" wire:click="toggleSort">
            {{ $sortOrder === 'latest' ? 'Latest' : 'Oldest' }}
        </x-forms.sm-filter-button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($projects as $project)
            <x-projects.card :project="$project" />
        @empty
            <div class="rounded-xl bg-slate-950/40 p-8 text-center ring-1 ring-white/5 col-span-full">
                <i class="fa-solid fa-inbox text-4xl text-slate-600 mb-3"></i>
                <p class="text-slate-400">No projects found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $projects->links('custom-pagination', ['pagename' => 'projects_page']) }}
    </div>
</div>