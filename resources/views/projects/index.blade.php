<x-layout>
    <x-slot:title>Projects</x-slot:title>

    <div class="max-w-7xl mx-auto p-6 space-y-6">

        <h1 class="text-3xl font-bold text-secondary">Projects all data</h1>

        @forelse ($projects as $project)
            <div class="border border-gray-300 rounded-lg p-4 bg-white">

                {{-- project --}}
                <h2 class="text-xl font-semibold">
                    #{{ $project->id }} – {{ $project->title }}
                </h2>

                <p class="text-sm text-gray-600">
                    Status: <strong>{{ $project->status }}</strong> |
                    Public: {{ $project->is_public ? 'true' : 'false' }} |
                    Created: {{ $project->created_at }}
                </p>

                <p class="mt-2">
                    {{ $project->description ?? 'NO DESCRIPTION' }}
                </p>

                {{-- project owner --}}
                <div class="mt-4">
                    <h3 class="font-semibold">Project owner</h3>
                    <div class="bg-gray-100 p-2 text-xs overflow-x-auto">
                        {{ $project->user }}
                    </div>
                </div>

                {{-- project Members (pivot data) --}}
                <div class="mt-4">
                    <h3 class="font-semibold">Members (project_user)</h3>

                    @forelse ($project->users as $user)
                        <div class="bg-gray-100 p-2 text-xs overflow-x-auto mb-2">
                            User #{{ $user->id }} ({{ $user->username }})
                            Pivot role: {{ $user->pivot->role }}
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No members</p>
                    @endforelse
                </div>

                {{-- tasks --}}
                <div class="mt-4">
                    <h3 class="font-semibold">Tasks</h3>

                    @forelse ($project->tasks as $task)
                        <div class="border border-gray-200 rounded p-3 mb-3">
                            <p>
                                <strong>#{{ $task->id }} – {{ $task->title }}</strong>
                            </p>
                            <p>Status: {{ $task->status }}</p>
                            <p>Due: {{ $task->due_date ?? 'none' }}</p>
                            <p>Completed at: {{ $task->completed_at ?? 'not completed' }}</p>

                            {{-- entries --}}
                            <div class="mt-2 ml-4">
                                <h4 class="font-semibold text-sm">Entries</h4>

                                @forelse ($task->entries as $entry)
                                    <div class="bg-gray-50 p-2 text-xs overflow-x-auto mb-2">
                                        Entry #{{ $entry->id }}
                                        User: {{ $entry->user_id }}
                                        Date: {{ $entry->work_date }}
                                        Minutes: {{ $entry->minutes }}
                                        Description: {{ $entry->description }}
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500">No entries</p>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No tasks</p>
                    @endforelse
                </div>

            </div>
        @empty
            <p class="text-gray-500">No projects found.</p>
        @endforelse

    </div>
</x-layout>