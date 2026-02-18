<x-layout>
    <x-slot:title>{{ $project->title }}</x-slot:title>
    <div class="relative min-h-screen w-full bg-darker overflow-hidden">
    <div class="relative mx-auto max-w-7xl px-6 py-16">
        <div class="rounded-3xl bg-darker shadow-2xl ring-1 ring-white/5">

            {{-- Header --}}
            <div class="border-b border-white/5 p-10">
                <h1 class="text-4xl font-bold text-textcol">
                    Edit Project
                </h1>
                @can('update', $project)
                <p class="mt-2 text-textcol2">
                    Change project details and manage members.
                </p>
                @else
                <p class="mt-2 text-textcol">
                    You cannot update project details or members, if the project is marked as 
                    <span class="inline-flex items-center gap-1 rounded-full bg-green-500/10 px-2 py-0.5">
                        <span class="text-xs text-green-400">Finished</span>
                    </span>
                    
                    or
                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-500/10 px-2 py-0.5">
                        <span class="text-xs text-gray-400">Archived</span>
                    </span>
                    
                </p>
                @endcan
            </div>

            <div class="p-10 space-y-10">

                {{-- Project settings --}}
                <div>
                    <form method="POST" action="{{ route('projects.update', $project) }}"
                        class="grid grid-cols-2 gap-8">
                        @csrf
                        @method('PUT')
                       @can('update', $project) 
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-textcol">Title</label>
                                <x-forms.input class="w-[100%]" type="text" name="title"
                                    value="{{ old('title', $project->title) }}" required />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-textcol">Description</label>
                                <x-forms.textarea class="w-[100%]" name="description" rows="4">
                                    {{ old('description', $project->description) }}
                                </x-forms.textarea>
                            </div>

                            <div class="flex justify-between">

                                <div>
                                    <label class="block text-sm font-medium text-textcol">Visibility</label>
                                    <x-forms.select-dropdown name="is_public" :selected="$project->is_public"
                                        :options="['0' => 'Private', '1' => 'Public']" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-textcol">Status</label>
                                    <x-forms.select-dropdown name="status" :selected="$project->status"
                                        :options="['active' => 'Active', 'on-hold' => 'On Hold', 'finished' => 'Finished']" />
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <x-forms.button>
                                    <i class="fa-solid fa-save mr-2"></i>Save Changes
                                </x-forms.button>

                                <x-forms.button :secondary="true" href="{{ route('projects.show', $project) }}">
                                    <i class="fa-solid fa-cancel mr-2"></i>Cancel
                                </x-forms.button>
                            </div>
                        @else
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-textcol">Title (readonly)</label>
                                <x-forms.input readonly class="w-[100%] text-textcol2" type="text" name="title"
                                    value="{{ old('title', $project->title) }}" required />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-textcol">Description (readonly)</label>
                                <x-forms.textarea readonly class="w-[100%] text-textcol2" name="description" rows="4">
                                    {{ old('description', $project->description) }}
                                </x-forms.textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-textcol">Status {{ $project->status }}</label>
                            </div>
                        @endcan
                    
                        @can('restore', $project)
                        <div class="flex justify-between">
                            {{-- make it a danger button later | tell the user it will make project 'active' --}}
                            <x-forms.button href="{{ route('projects.restore', $project) }}">
                                <i class="fa-solid fa-recycle mr-2 text-green-300"></i>
                                <span class="text-green-300 font-bold">Restore Project</span>
                            </x-forms.button>
                            <x-forms.button :secondary="true" href="{{ route('projects.show', $project) }}">
                                <i class="fas fa-arrow-left mr-2"></i>Return
                            </x-forms.button>
                        </div>
                        @endcan
                        </div>
                        @can('update', $project)
                        <div class="w-full max-w-md">
                            <div id="search-app">
                                <label class="block text-sm font-medium text-textcol">Add New Members</label>
                                <user-search
                                    :initial-users='@json(old("users") ?? ($project->users->pluck("id") ?? []))'
                                ></user-search>

                            </div>
                        </div>
                        @endcan
                    </form>


                </div>

                {{-- Members Section --}}
                <div>
                    <div class="flex items-start justify-between gap-6 mb-6">
                        <h2 class="text-2xl font-bold text-textcol mt-2">Team Members</h2>
                    </div>

                    {{-- User cards grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <x-projects.user-card :user="$project->user" :project="$project" role="owner"
                            :userStats="$userStats" />

                        @foreach ($project->users as $user)
                            <div class="relative">
                                <x-projects.user-edit-card :user="$user" :project="$project" role="{{ $user->pivot->role }}"
                                    :userStats="$userStats" />
                            </div>
                        @endforeach
                    </div>

                    {{-- Delete project  (ONLY if it is ARCHIVED, otherwise nuh uh)--}}
                    @can('delete', $project)
                      <div class="mt-8">
                        <form method="POST" action="{{ route('projects.destroy', $project) }}">
                            @method('DELETE')
                            {{-- make it a danger button later --}}
                            <x-forms.danger-button
                            :confirm="true"
                            confirmMessage="Deleting this project will delete all of it's tasks, as well as the entries made will be forgotten forever!"
                            confirmTitle="Warning!"
                            > 
                                <i class="fa-solid fa-trash mr-2 text-red-300"></i>
                                <span class="text-red-300 font-bold">Delete Project</span>
                            </x-forms.danger-button>
                        </form>
                    </div>  
                    @elsecan('softDelete', $project)
                        <div class="mt-8">
                            {{-- make it a danger button later --}}
                            <x-forms.danger-button href="{{ route('projects.archive', $project) }}"
                            :confirm="true"
                            confirmMessage="Archiving this project will make it not accessible for anyone else"
                            confirmTitle="Warning!"
                            >
                                <i class="fa-solid fa-trash mr-2 text-red-300"></i>
                                <span class="text-red-300 font-bold">Archive Project</span>
                            </x-forms.danger-button>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
</x-layout>