<x-layout>
    <x-slot:title>{{ $task->title }}</x-slot:title>
    @php
        $statusConfig = [
            'in_progress' => [
                'bg' => 'bg-yellow-500/10',
                'text' => 'text-yellow-400',
                'icon' => 'fa-solid fa-spinner',
                'label' => 'In Progress'
            ],
            'completed' => [
                'bg' => 'bg-green-500/10',
                'text' => 'text-green-400',
                'icon' => 'fa-solid fa-check-circle',
                'label' => 'Completed'
            ],
            'archived' => [
                'bg' => 'bg-gray-500/10',
                'text' => 'text-gray-400',
                'icon' => 'fa-solid fa-archive',
                'label' => 'Archived'
            ],
        ];

        $status = $statusConfig[$task->status] ?? $statusConfig['archived'];

        if (!$task->due_date) {
            $dueDateClass = 'text-textcol2';
        } else {
            $isDueToday = $task->due_date->isToday();
            $isOverdue = $task->due_date->isPast() && $task->status !== 'completed';
            $dueDateClass = $isOverdue
                ? 'text-red-400'
                : ($isDueToday ? 'text-yellow-400' : 'text-primary');
        }
    @endphp

    <div class="relative min-h-screen w-full bg-darker overflow-hidden">
        <div
            class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-gradient-to-br from-indigo-500/20 to-purple-500/0 blur-3xl">
        </div>
        <div
            class="absolute -right-32 -bottom-32 h-96 w-96 rounded-full bg-gradient-to-br from-purple-500/20 to-indigo-500/0 blur-3xl">
        </div>

        <div class="relative mx-auto max-w-7xl px-6 py-16">
            <div class="rounded-3xl bg-darker shadow-2xl ring-1 ring-white/5">

                {{-- Header --}}
                <div class="border-b border-white/5 p-10">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-4xl font-bold text-textcol">
                                {{ $task->title }}
                            </h1>
                            <p class="mt-4 max-w-3xl text-lg text-textcol2">
                                {{ $task->description ?: 'No description provided.' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 rounded-full {{ $status['bg'] }} px-3 py-1">
                            <i class="{{ $status['icon'] }} {{ $status['text'] }}"></i>
                            <span class="text-sm font-semibold {{ $status['text'] }}">
                                {{ $status['label'] }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-10 space-y-10">

                    {{-- Meta --}}
                    <div class="flex flex-wrap items-center gap-6">
                        @if($task->due_date)
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-calendar {{ $dueDateClass }}"></i>
                                <span class="text-sm font-medium {{ $dueDateClass }}">
                                    Due {{ $task->due_date->format('M d, Y') }}
                                </span>
                            </div>
                        @endif

                        <div class="flex items-center gap-2 rounded-xl bg-primary/10 px-4 py-2">
                            <i class="fa-solid fa-clock text-primary"></i>
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-primary">Total Time</span>
                                <span class="text-sm font-semibold text-textcol">
                                    <x-minutes-to-hours :minutes="$taskStats->total_minutes" />
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 rounded-xl bg-primary/10 px-4 py-2">
                            <i class="fas fa-pencil-alt text-primary"></i>
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-primary">Entries</span>
                                <span class="text-sm font-semibold text-textcol">
                                    {{ $taskStats->total_entry_count }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap gap-3">
                        <x-forms.button href="{{ route('projects.show', $task->project) }}">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Project
                        </x-forms.button>

                        {{-- <x-forms.button :href="route('projects.entries.create', [$task->project, $task])"> --}}
                            <x-forms.button>
                                <i class="fas fa-plus mr-2"></i>
                                Add Entry
                            </x-forms.button>

                            <x-forms.button :secondary="true"
                                href="{{ route('projects.tasks.edit', [$task->project, $task]) }}">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Task
                            </x-forms.button>
                    </div>

                    {{-- Task Entries --}}
                    <div class="space-y-4">
                        <h2 class="text-2xl font-bold text-textcol">Task Entries</h2>

                        <div class="space-y-4">
                            @forelse ($entries as $entry)
                                <x-projects.entry-card :entry="$entry" />
                            @empty
                                <div class="rounded-xl bg-slate-950/40 p-8 text-center ring-1 ring-white/5">
                                    <i class="fa-solid fa-inbox text-4xl text-slate-600 mb-3"></i>
                                    <p class="text-slate-400">No entries for this task yet.</p>
                                </div>
                            @endforelse
                        </div>

                        {{ $entries->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-layout>