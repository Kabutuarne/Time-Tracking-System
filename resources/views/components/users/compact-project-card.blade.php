@props([
    'project',
    'user',
    'owned' => false
])

<div
    class="group relative overflow-hidden rounded-lg bg-slate-950/40 ring-1 ring-white/5 transition hover:ring-primary/30 w-full max-w-[520px]">

    <div
        class="absolute -right-10 -top-10 h-24 w-24 rounded-full bg-primary/10 blur-2xl opacity-60 transition group-hover:scale-125">
    </div>

    <div class="relative p-4 flex flex-col gap-3">
        {{-- header --}}
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                <h3 class="text-sm font-semibold text-textcol truncate">
                    {{ $project->title }}
                </h3>
                <p class="text-xs text-textcol2 line-clamp-2">
                    {{ $project->description }}
                </p>
            </div>

            <span class="text-[10px] text-slate-400 whitespace-nowrap">
                {{ $project->created_at->diffForHumans() }}
            </span>
        </div>

        {{-- stats --}}
        <div class="flex items-center gap-3">
            <x-users.compact-card-count type="members" :project="$project" />

            <x-users.compact-card-count type="tasks" :project="$project" />

            <x-users.compact-card-count type="active-tasks" :project="$project" />
        </div>

        {{-- actions --}}
        <div class="flex justify-end gap-3">

            <x-forms.sm-button :href="route('projects.show', $project)">
                View
            </x-forms.sm-button>
            @if(!$owned)
                @if(Auth::id() == $user->id)
                <form method="POST" action="{{ route('projects.users.destroy', [$project, $user]) }}">
                    @csrf
                    @method('DELETE')

                    <x-forms.sm-button :secondary="true">
                        Leave
                    </x-forms.sm-button>
                </form>
                @endif
            @endif
        </div>
    </div>
</div>