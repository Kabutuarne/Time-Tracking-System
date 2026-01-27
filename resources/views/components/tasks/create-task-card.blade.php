@props([
    'project',
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

            <div class="border-b border-white/5 p-10">
                <h1 class="text-4xl font-bold text-textcol">
                    {{ $project->title }}
                </h1>
                <p class="mt-4 max-w-3xl text-lg text-textcol2">
                    {{ $project->description }}
                </p>

            </div>

            <div class="p-10 space-y-auto">
                <form method="POST" action="{{ route('projects.tasks.store', $project) }}">
                    @csrf

                    <div class="mt-4 flex flex-wrap gap-4 items-start">
                        {{-- task title --}}
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-semibold text-textcol2 mb-2">
                                Title
                            </label>
                            <x-forms.input required id="title" type="text" placeholder="Task title" name="title"
                               :value="old('title')" maxlength="100" required autofocus />
                            <x-forms.input-error :messages="$errors->get('title')" class="mt-2" />
                          </div>

                       {{-- due date --}}
                        <div class="w-[220px]">
                            <label class="block text-sm font-semibold text-textcol2 mb-2">
                                Due Date (optional)
                            </label>
                            <x-forms.datetime-picker id="due_date" name="due_date" />
                            <x-forms.input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>
                    </div>

                    {{-- task description --}}
                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-textcol2 mb-2">
                            Description
                        </label>
                        <x-forms.textarea id="description" name="description" rows="4"
                            placeholder="Task description" class="w-full"></x-forms.textarea>
                        <x-forms.input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex flex-wrap gap-3 align-middle justify-end mt-6">
                        <x-forms.button href="{{ route('projects.show', $project) }}">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </x-forms.button>
                        <x-forms.button :secondary="true">
                            <i class="fas fa-plus mr-2"></i>Add Task
                        </x-forms.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>