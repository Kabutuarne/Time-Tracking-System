<div class="relative min-h-screen w-full bg-darker overflow-hidden">
    <div
        class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-gradient-to-br from-indigo-500/20 to-purple-500/0 blur-3xl">
    </div>
    <div
        class="absolute -right-32 -bottom-32 h-96 w-96 rounded-full bg-gradient-to-br from-purple-500/20 to-indigo-500/0 blur-3xl">
    </div>

    <div class="relative mx-auto max-w-7xl px-6 py-16">
        <div class="rounded-3xl bg-darker shadow-2xl ring-1 ring-white/5">

            <div class="border-b border-white/5 p-10">
                <h1 class="text-4xl font-bold text-textcol">
                    {{ $project['title'] }}
                </h1>
                <p class="mt-4 max-w-3xl text-lg text-textcol2">
                    {{ $project['description'] }}
                </p>

                {{-- Project stats --}}
                <div class="mt-6 flex items-center gap-6">
                    <div class="flex items-center gap-2 rounded-xl bg-primary/10 px-4 py-2">
                        <i class="fa-solid fa-clock text-primary"></i>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-primary">Total Time</span>
                            <span class="text-sm font-semibold text-textcol">
                                <x-minutes-to-hours minutes="{{ $projectStats[$project->id]->total_minutes ?? 0 }}" />
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 rounded-xl bg-secondary/10 px-4 py-2">
                        <i class="fa-solid fa-list-check text-secondary"></i>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-secondary">Total Tasks</span>
                            <span
                                class="text-sm font-semibold text-textcol">{{ $projectStats[$project->id]->total_task_count ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 rounded-xl bg-primary/10 px-4 py-2">
                        <i class="fas fa-pencil-alt text-primary"></i>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-primary">Total Entries</span>
                            <span
                                class="text-sm font-semibold text-textcol">{{ $projectStats[$project->id]->total_entry_count ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 rounded-xl bg-secondary/10 px-4 py-2">
                        <i class="fas fa-users text-secondary"></i>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-secondary">Team Size</span>
                            {{-- Users + the owner --}}
                            <span
                                class="text-sm font-semibold text-textcol">{{ ($project->users->count() ?? 0) + 1}}</span>

                        </div>
                    </div>
                    <div class="flex items-center gap-2 rounded-xl bg-primary/10 px-4 py-2">
                        <i
                            class="{{ ($project->is_public ? 'fa-solid fa-globe' : 'fa-solid fa-lock') }} text-primary"></i>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-primary">Visibility</span>
                            {{-- Users + the owner --}}
                            <span
                                class="text-sm font-semibold text-textcol">{{ ($project->is_public ? 'Public' : 'Private') }}</span>

                        </div>
                    </div>
                </div>
            </div>

            <div class="p-10 space-y-8">
                {{-- Action buttons --}}
                {{-- @auth --}}
                {{-- @if (auth()->user()->is($project->user) || $project->users->contains(auth()->user())) --}}
                <div class="flex flex-wrap gap-3">
                    <x-forms.button :href="route('tasks.create', $project)">
                        <i class="fa-solid fa-tasks mr-2"></i>Add Task
                    </x-forms.button>
                    {{-- @if (auth()->user()->is($project->user) || auth()->user()->isManagerOf($project)) --}}
                    <x-forms.button :secondary="true" :href="route('projects.edit', $project)">
                        <i class="fa-solid fa-edit mr-2"></i>Edit Project
                    </x-forms.button>
                    <x-forms.button :secondary="true" :href="route('users.index', $project)">
                        <i class="fas fa-user-plus mr-2"></i>Add Member
                    </x-forms.button>
                    {{-- @endif --}}
                </div>
                {{-- @endif --}}
                {{-- @endauth --}}

                {{-- Entries and tasks --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Project Entries --}}
                    <div class="lg:col-span-2 space-y-4">
                        <h2 class="text-2xl font-bold text-textcol">Project Entries</h2>
                        <div class="space-y-4">
                            @forelse ($entries ?? [] as $entry)
                                <x-projects.entry-card :entry="$entry" />
                            @empty
                                <div class="rounded-xl bg-slate-950/40 p-8 text-center ring-1 ring-white/5">
                                    <i class="fa-solid fa-inbox text-4xl text-slate-600 mb-3"></i>
                                    <p class="text-slate-400">No entries yet.</p>
                                </div>
                            @endforelse
                        </div>
                        {{ $entries->links() }}
                    </div>

                    {{-- Project tasks --}}
                    <div class="space-y-4">
                        <h2 class="text-2xl font-bold text-textcol">Tasks</h2>
                        <div class="space-y-3">
                            @forelse ($tasks ?? [] as $task)
                                <x-projects.task-card :task="$task" />
                            @empty
                                <div class="rounded-xl bg-slate-950/40 p-6 text-center ring-1 ring-white/5">
                                    <i class="fa-solid fa-tasks text-3xl text-slate-600 mb-2"></i>
                                    <p class="text-slate-400 text-sm">No tasks yet.</p>
                                </div>
                            @endforelse
                        </div>
                        {{ $tasks->links() }}

                        {{-- Admin Actions --}}
                        @auth
                            @if (auth()->user()->is($project->user))
                                <div class="rounded-2xl bg-slate-950/40 p-6 ring-1 ring-white/5 mt-6">
                                    <h3 class="text-lg font-semibold text-textcol mb-4">Admin Actions</h3>
                                    <div class="space-y-3">
                                        {{-- Will be accessible only to Owner and Manager --}}
                                        <x-forms.button class="w-full" :href="route('projects.edit', $project)">
                                            <i class="fa-solid fa-edit mr-2"></i>Edit Project
                                        </x-forms.button>
                                        <form method="POST" action="{{ route('projects.destroy', $project) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this project?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-forms.button type="submit" :secondary="true" class="w-full">
                                                <i class="fa-solid fa-trash mr-2"></i>Delete Project
                                            </x-forms.button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                {{-- Team members --}}
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-textcol mb-4">Team Members</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <x-projects.user-card :user="$project->user" role="owner" :userStats="$userStats" />

                        @foreach ($project->users as $user)
                            <x-projects.user-card :user="$user" role="{{ $user->pivot->role }}" :userStats="$userStats" />
                        @endforeach
                    </div>

                    @if ($project->users->isEmpty())
                        <p class="text-slate-400 text-sm mt-4">No additional members yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>