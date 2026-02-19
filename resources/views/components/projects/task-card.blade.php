@props([
    'task' => $task,
    'project' => $project,
])
@php
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
            
            <x-projects.task-status :status="$task->status" />
        </div>

            <p class="text-xs text-textcol2 mb-2 line-clamp-2">{{ $task->description }}</p>

        @if($task->due_date)
            <div class="flex items-center gap-1.5 mb-2">
                <span class="{{ $dueDateClass }} text-xs font-medium">Due: </span>
                <span class="text-xs font-medium {{ $dueDateClass }}">
                    {{ $task->due_date->format('M d, Y') }}
                </span>
            </div>
        @endif


        @if($task->status === 'completed')
            @can('softDelete', $task)
            <x-forms.danger-sm-button href="{{ route('projects.tasks.archived', [$project, $task]) }}"
                :confirm="true"
                confirmTitle="Attention!"
                confirmMessage="This action will make this task invisible and inaccessible to all users, besides managers and the owner!"
            >
                    Archive Task
            </x-forms.danger-sm-button>
            @endcan
        @elseif($task->status === 'in_progress')
            @can('createEntry', $task)
            <x-forms.sm-button :secondary="true" href="{{ route('projects.tasks.entries.create', [$project, $task]) }}">
                Add Entry
            </x-forms.sm-button>   
            @endcan
           
            @can('softDelete', $task)
            <x-forms.danger-sm-button href="{{ route('projects.tasks.archived', [$project, $task]) }}"
                :confirm="true"
                confirmTitle="Attention!"
                confirmMessage="This action will make this task invisible and inaccessible to all users, besides managers and the owner!"
            >
                Archive Task
            </x-forms.danger-sm-button>       
            @endcan
      
        @elseif($task->status === 'archived')
            @can('delete', $task)
                <form method="POST"
                    action="{{ route('projects.tasks.destroy', [$task->project, $task]) }}"
                    class="absolute bottom-[-7%] left-[15%]">
                    @csrf
                    @method('DELETE')
                    <x-forms.trash-button
                            :confirm="true"
                            confirmTitle="Warning!"
                            confirmMessage="This action will remove the task forever, and all entries for it will be forgotten!"
                        />
                </form>
            @endcan

        @endif

        <div class="flex items-center justify-between border-t border-white/5 pt-2">
            <x-forms.sm-button :href="route('projects.tasks.show', [$project, $task])">View</x-forms.sm-button>
            @can('update', $task)
                <x-forms.sm-button :href="route('projects.tasks.edit', [$project, $task])">Edit</x-forms.sm-button>
            @endcan
        </div>
    </div>
</div>