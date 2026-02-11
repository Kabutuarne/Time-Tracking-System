@props([
    'entry',
    'project'
])

<div class="group relative rounded-xl bg-slate-950/40 ring-1 ring-white/5 transition-all duration-300 hover:ring-primary/30">
    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-gradient-to-br from-primary/10 to-secondary/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70"></div>
    
    <div class="relative p-4">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                            <span class="text-xs font-semibold text-primary">
                                {{ substr($entry->user->username, 0, 2) }}
                            </span>
                        </div>
                        <div>
                            @if ($project->users->contains($entry->user))
                                <p class="text-sm font-medium text-textcol">
                                    {{ $entry->user->username }}
                                </p>
                            @else
                                <p class="text-sm font-medium italic text-primary/100">
                                    {{ $entry->user->username }}*
                                </p>
                            @endif
                            
                            <p class="text-xs text-slate-400">{{ $entry->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                
                @if($entry->description)
                    <p class="text-sm text-textcol2 mt-2 line-clamp-1">{{ $entry->description }}</p>
                @endif
                
                @if($entry->task)
                <div class="flex mt-3 gap-3">
                    <div class="inline-flex items-center gap-1.5 rounded-full bg-secondary/10 px-3 py-1">
                        <i class="fa-solid fa-tasks text-secondary text-xs"></i>
                        <span class="text-xs font-medium text-secondary">{{ $entry->task->title }}</span>
                    </div>
                    <x-projects.task-status :status="$entry->task->status" />
                    </div>
                @endif
            </div>
            
            <div class="flex flex-col items-end gap-2">
                <div class="rounded-lg bg-primary/10 px-3 py-1.5">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-clock text-primary text-xs"></i>
                        <span class="text-sm font-semibold text-primary"><x-minutes-to-hours minutes="{{ $entry->minutes }}" /></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4 flex items-left gap-2 border-t border-white/5 pt-3">
                @can('update', $entry)
                 <x-forms.sm-button 
                    href="{{ route('projects.tasks.entries.edit', [
                        'project' => $entry->project,
                        'task' => $entry->task,
                        'entry' => $entry
                    ]) }}"
                >
                    Edit Entry
                </x-forms.sm-button>
                @endcan
                @can('delete', $entry)
                <form method="POST"
                            action="{{ route('projects.tasks.entries.destroy', [$project, $entry->task, $entry]) }}"
                            class="absolute bottom-[7%] right-0"
                    >
                        @csrf
                        @method('DELETE')
                        <x-forms.trash-button
                            confirm="true"
                            confirm-title="Warning!"
                            confirm-message="This action will remove the entry forever, and time spent on the task will be forgotten!"
                        />
                    </form>
                @endcan
        </div>
    </div>
</div>