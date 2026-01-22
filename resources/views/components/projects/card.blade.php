<!-- From Uiverse.io by themrsami -->
<div class="group relative w-[380px]">
    <div
        class="relative overflow-hidden rounded-2xl bg-darker shadow-2xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-primary/0">
        <div
            class="absolute -left-16 -top-16 h-32 w-32 rounded-full bg-gradient-to-br from-indigo-500/20 to-purple-500/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70">
        </div>
        <div
            class="absolute -right-16 -bottom-16 h-32 w-32 rounded-full bg-gradient-to-br from-purple-500/20 to-indigo-500/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70">
        </div>

        <div class="relative p-6">

            <div class="mt-4 space-y-2">
                <h3 class="text-xl font-semibold text-textcol">
                    {{ $project['title'] }}
                </h3>
                <p class="text-textcol2">
                    {{ $project['description'] }}
                </p>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <div class="group/avatar relative">
                    <div
                        class="absolute -inset-1 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 opacity-75 blur transition-all duration-300 group-hover/avatar:opacity-100">
                    </div>
                    {{-- <div class="relative h-12 w-12 rounded-full bg-slate-950 ring-2 ring-slate-950">
                        <svg fill="currentColor" viewBox="0 0 24 24" class="h-12 w-12 text-indigo-500">
                            <path
                                d="M7.5 6.5C7.5 8.981 9.519 11 12 11s4.5-2.019 4.5-4.5S14.481 2 12 2 7.5 4.019 7.5 6.5zM20 21h1v-1c0-3.859-3.141-7-7-7h-4c-3.86 0-7 3.141-7 7v1h17z">
                            </path>
                        </svg>
                    </div> --}}
                </div>
                <div>
                    <h4 class="font-semibold text-white">{{ $project->user->username }}</h4>
                    <p class="text-sm text-slate-400">{{ $project->user->first_name }}
                        {{ $project->user->last_name }}
                    </p>
                </div>
                @auth

                @endauth
                <x-projects.card-role :project="$project" />
            </div>

            <div class="mt-6 grid grid-cols-3 gap-4">
                <x-projects.card-count type="members" :project="$project" />
                <x-projects.card-count type="tasks" :project="$project" />
                <x-projects.card-count type="entries" :project="$project" />
            </div>

            <div class="mt-6 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-forms.sm-button :secondary="false" href="projects/view">View</x-forms.sm-button>
                    {{-- If user owns this one --}}
                    @auth
                        @if (auth()->user()->is($project->user))
                            <x-forms.sm-button :secondary="true">Edit</x-forms.sm-button>
                        @endif
                    @endauth
                </div>
                <span class="text-sm text-slate-400">Created {{ $project->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</div>