<x-layout>
<x-slot:title>{{ $task->title }}</x-slot:title>
@php
    $statuses = [
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'archived' => 'Archived',
    ];
@endphp

<div class="relative min-h-screen w-full bg-darker overflow-hidden">
    <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-gradient-to-br from-indigo-500/20 to-purple-500/0 blur-3xl"></div>
    <div class="absolute -right-32 -bottom-32 h-96 w-96 rounded-full bg-gradient-to-br from-purple-500/20 to-indigo-500/0 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl px-6 py-16">
        <div class="rounded-3xl bg-darker shadow-2xl ring-1 ring-white/5 p-10">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <form method="POST" action="{{ route('projects.tasks.update', [$task->project, $task]) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">

                            <h1 class="text-4xl font-bold text-textcol">Edit Task</h1>

                            {{-- title --}}
                            <div>
                                <label class="block text-sm font-semibold text-textcol2 mb-2">Title</label>
                                <x-forms.input
                                    id="title"
                                    type="text"
                                    placeholder="Task title"
                                    name="title"
                                    :value="old('title', $task->title)"
                                    maxlength="100"
                                    required
                                    autofocus
                                />
                                <x-forms.input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            {{-- description --}}
                            <div>
                                <label class="block text-sm font-semibold text-textcol2 mb-2">Description</label>
                                <x-forms.textarea
                                    id="description"
                                    name="description"
                                    rows="4"
                                    placeholder="Task description"
                                >{{ old('description', $task->description) }}</x-forms.textarea>
                                <x-forms.input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            {{-- due date --}}
                            <div>
                                <label class="block text-sm font-semibold text-textcol2 mb-2">Due date (optional)</label>
                                <x-forms.datetime-picker
                                    name="due_date"
                                    :value="old('due_date', optional($task->due_date)->format('Y-m-d\TH:i'))"
                                />
                                <x-forms.input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>

                            {{-- status --}}
                            <div>
                                <label class="block text-sm font-semibold text-textcol2 mb-2">Status</label>
                                {{-- <select
                                    name="status"
                                    class="rounded-xl bg-slate-950/50 border border-white/10 px-4 py-3 text-textcol focus:outline-none focus:ring-2 focus:ring-primary"
                                >
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected(old('status', $task->status) === $value)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select> --}}
                                <x-forms.select-dropdown name="status" :selected="$task->status"
                                        :options="['in_progress' => 'In Progress', 'completed' => 'Completed', 'archived' => 'Archived']" />
                            </div>

                        </div>

                        {{-- stats --}}
                        <div class="flex flex-wrap gap-6 mt-6 border-t border-white/5 pt-6">
                            <div class="flex items-center gap-2 rounded-xl bg-primary/10 px-4 py-2">
                                <i class="fa-solid fa-clock text-primary"></i>
                                <div>
                                    <div class="text-xs font-semibold text-primary">Total Time</div>
                                    <div class="text-sm font-semibold text-textcol">
                                        <x-minutes-to-hours :minutes="$taskStats->total_minutes" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 rounded-xl bg-primary/10 px-4 py-2">
                                <i class="fas fa-pencil-alt text-primary"></i>
                                <div>
                                    <div class="text-xs font-semibold text-primary">Entries</div>
                                    <div class="text-sm font-semibold text-textcol">
                                        {{ $taskStats->total_entry_count }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- actions --}}
                        <div class="flex flex-wrap gap-3 mt-6">
                            <x-forms.button type="submit">
                                <i class="fas fa-save mr-2"></i>
                                Save Changes
                            </x-forms.button>

                            <x-forms.button :secondary="true" href="{{ route('projects.show', $task->project) }}">
                                Cancel
                            </x-forms.button>
                        </div>
                    </form>
                </div>
                {{-- entries --}}
                <div class="lg:col-span-2 space-y-4">
                    <h2 class="text-2xl font-bold text-textcol">Task Entries</h2>
                    <div class="space-y-4">
                        @forelse ($entries as $entry)
                            <div class="relative">
                                <x-projects.entry-card :entry="$entry" :project="$task->project" />

                                <form method="POST"
                                      action="{{ route('projects.tasks.entries.destroy', [$task->project, $task, $entry]) }}"
                                      class="absolute bottom-1 right-0"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <x-forms.trash-button></x-forms.trash-button>
                                </form>
                            </div>
                        @empty
                            <div class="rounded-xl bg-slate-950/40 p-8 text-center ring-1 ring-white/5">
                                <p class="text-slate-400">No entries yet.</p>
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