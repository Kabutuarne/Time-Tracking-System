<div
    class="group relative overflow-hidden rounded-xl bg-slate-950/40 ring-1 ring-white/5 transition-all duration-300 hover:ring-primary/30 w-[380px]">
    <div
        class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-gradient-to-br from-primary/10 to-secondary/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70">
    </div>

    <div class="relative p-6">
        <div class="space-y-2">
            <h3 class="text-xl font-semibold text-textcol">
                {{ $project['title'] }}
            </h3>
            <p class="text-sm text-textcol2 line-clamp-4">
                {{ $project['description'] }}
            </p>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                <span class="text-sm font-semibold text-primary">
                    {{ substr($project->user->username, 0, 2) }}
                </span>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-textcol">{{ $project->user->username }}</h4>
                <p class="text-xs text-slate-400">{{ $project->user->first_name }} {{ $project->user->last_name }}</p>
            </div>
            {{-- @auth will add later meybe
            <x-projects.card-role :role="dd(Auth::user()->projects->find($project->id)?->pivot->role)" />
            @endauth --}}
        </div>

        <div class="mt-6 grid grid-cols-3 gap-3">
            <x-projects.card-count type="members" :project="$project" />
            <x-projects.card-count type="tasks" :project="$project" />
            <x-projects.card-count type="entries" :project="$project" />
        </div>

        <div class="mt-6 flex items-center justify-between border-t border-white/5 pt-4">
            <div class="flex items-center gap-2">
                <x-forms.sm-button :secondary="false" :href="route('projects.show', $project)">View</x-forms.sm-button>
                @can('update', $project)
                    <x-forms.sm-button :secondary="true" href="">Edit</x-forms.sm-button>
                @endcan

            </div>
            <span class="text-xs text-slate-400">{{ $project->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>