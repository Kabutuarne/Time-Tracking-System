@props([
    'project',
    'task'
])

<div class="relative min-h-screen w-full bg-darker overflow-hidden">
    <div
        class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-gradient-to-br from-indigo-500/20 to-purple-500/0 blur-3xl">
    </div>
    <div
        class="absolute -right-32 -bottom-32 h-96 w-96 rounded-full bg-gradient-to-br from-purple-500/20 to-indigo-500/0 blur-3xl">
    </div>

    <div class="relative mx-auto max-w-7xl px-6 py-16">
        <div class="rounded-3xl bg-darker shadow-2xl ring-1 ring-white/5">

            {{-- header --}}
            <div class="border-b border-white/5 p-10">
                <h1 class="text-4xl font-bold text-textcol">
                    Edit Entry
                </h1>

                <div class="mt-4 space-y-1">
                    <p class="text-sm text-textcol2">
                        Project
                    </p>
                    <p class="text-lg font-semibold text-textcol">
                        {{ $task->project->title }}
                    </p>

                    <p class="mt-3 text-sm text-textcol2">
                        Task
                    </p>
                    <p class="text-lg font-semibold text-textcol">
                        {{ $task->title }}
                    </p>
                </div>
            </div>

            {{-- form --}}
            <div class="p-10">
                <form method="POST"
                 action="{{ route('projects.tasks.entries.store', [
                        'project' => $project,
                        'task' => $task,
                    ]) }}"
                    >
                <div class="flex justify-between">
                    @csrf
                    <div>
                        {{-- description --}}
                        <div class="mt-4 w-[500px]">
                            <label class="block text-sm font-semibold text-textcol2 mb-2">
                                Description
                            </label>
                            <x-forms.textarea
                                name="description"
                                rows="4"
                                placeholder="What did you actually do?"
                                class="w-full"
                                limit="255"
                            >
                                {{ old('description', $entry->description) }}
                            </x-forms.textarea>
                            <x-forms.input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        {{-- work date --}}
                        <div class="mt-4 w-[220px]">
                            <label class="block text-sm font-semibold text-textcol2 mb-2">
                                Work Date
                            </label>
                            <x-forms.datetime-picker
                                name="work_date"
                                value="{{ $entry ? now()->format('Y-m-d\TH:i') : $entry->description}}"
                                required
                            />
                            <x-forms.input-error :messages="$errors->get('work_date')" class="mt-2" />
                        </div>

                        {{-- minutes --}}
                        <div class="mt-4 w-[200px]">
                            <label class="block text-sm font-semibold text-textcol2 mb-2">
                                Minutes Worked
                            </label>
                            <x-forms.minute-picker 
                                name="minutes"
                                :min="5" 
                                :max="480" 
                                :step="5"
                                :value="$entry->minutes"
                            />
                            <x-forms.input-error :messages="$errors->get('minutes')" class="mt-2" />
                        </div>
                    </div>
                </div>
                    <div class="flex flex-wrap gap-3 justify-start mt-6">
                        <x-forms.button href="{{ route('projects.show', $project) }}">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </x-forms.button>

                        <x-forms.button :secondary="true">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </x-forms.button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
