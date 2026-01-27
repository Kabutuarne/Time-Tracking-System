@props([
    'task' => $task,
    'project' => $project,
])
@php
    // Status colors and icons
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
    
    // due date handling
    if(!$task->due_date){
        $dueDateClass = 'text-textcol2';
    }else{
        $isDueToday = $task->due_date && $task->due_date->isToday();
        $isOverdue = $task->due_date && $task->due_date->isPast() && $task->status !== 'completed';
        $dueDateClass = $isOverdue ? 'text-red-400' : ($isDueToday ? 'text-yellow-400' : 'text-primary');
    }
@endphp

<div class="group relative overflow-hidden rounded-lg bg-slate-950/40 ring-1 ring-white/5 transition-all duration-300 hover:ring-primary/30">
    <div class="absolute -right-4 -top-4 h-16 w-16 rounded-full bg-gradient-to-br from-primary/10 to-secondary/0 blur-xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70"></div>
    
    <div class="relative p-3">
        <div class="flex items-start justify-between gap-2 mb-2">
            <h3 class="text-sm font-semibold text-textcol line-clamp-2 flex-1">{{ $task->title }}</h3>
            
            <div class="flex items-center gap-1 rounded-full {{ $status['bg'] }} px-2 py-0.5 shrink-0">
                <i class="{{ $status['icon'] }} {{ $status['text'] }} text-xs"></i>
            </div>
        </div>

            <p class="text-xs text-textcol2 mb-2 line-clamp-1">{{ $task->description }}</p>

        @if($task->due_date)
            <div class="flex items-center gap-1.5 mb-2">
                <span class="{{ $dueDateClass }} text-xs font-medium">Due: </span>
                <span class="text-xs font-medium {{ $dueDateClass }}">
                    {{ $task->due_date->format('M d, Y') }}
                </span>
            </div>
        @endif


        @if($task->status === 'completed')
            <div class="mb-2 rounded bg-green-500/10 px-2 py-1">
                <p class="text-xs text-green-400 font-medium">
                    Completed
                </p>
            </div>
            {{-- Can set active or archive only Manager and Owner --}}
            <x-forms.sm-button :secondary="true" href="{{ route('projects.tasks.complete', [$project, $task]) }}">
                Set Active
            </x-forms.sm-button>
            <x-forms.sm-button href="{{ route('projects.tasks.archived', [$project, $task]) }}">
                Archive Task
            </x-forms.sm-button>
        @elseif($task->status === 'in_progress')
            <div class="mb-2 rounded bg-yellow-500/10 px-2 py-1">
                <p class="text-xs text-yellow-400 font-medium">
                    Active
                </p>
            </div>
            {{-- Will be able to set completed in the entry --}}
            <x-forms.sm-button :secondary="true" href="{{ route('projects.tasks.complete', [$project, $task]) }}">
                Add Entry
            </x-forms.sm-button>
            {{-- Can archive only Manager and Owner --}}
            <x-forms.sm-button href="{{ route('projects.tasks.archived', [$project, $task]) }}">
                Archive Task
            </x-forms.sm-button>
            {{-- <x-forms.sm-button :secondary="true" :href="route('tasks.complete', $task)"> --}}
            
        @elseif($task->status === 'archived')
            <div class="mb-2 rounded bg-gray-500/10 px-2 py-1">
                <p class="text-xs text-gray-400 font-medium">
                    Archived
                </p>
            </div>
            {{-- Can set active or archive only Manager and Owner --}}
            <form method="POST"
                                      action="{{ route('projects.tasks.destroy', [$task->project, $task]) }}"
                                      class="absolute bottom-[-7%] left-[15%]"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <x-forms.trash-button></x-forms.trash-button>
                                </form>
        @endif

        <div class="flex items-center justify-between border-t border-white/5 pt-2">
            <x-forms.sm-button :href="route('projects.tasks.show', [$project, $task])">View</x-forms.sm-button>
            {{-- @auth --}}
                {{-- @if(auth()->user()->isManagerOf($task->project) || auth()->user()->is($task->project->user)) --}}
                    <x-forms.sm-button :href="route('projects.tasks.edit', [$project, $task])">Edit</x-forms.sm-button>
                {{-- @endif --}}
            {{-- @endauth --}}
        </div>
    </div>
</div>