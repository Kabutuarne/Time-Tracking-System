<x-layout>
    <x-slot:title>Project Creation</x-slot:title>
    <div class="relative min-h-screen w-full bg-darker overflow-hidden">
        <div class="relative mx-auto max-w-7xl px-6 py-16">
            <div class="rounded-3xl bg-darker shadow-2xl ring-1 ring-white/5">

                {{-- Header --}}
                <div class="border-b border-white/5 p-10">
                    <h1 class="text-4xl font-bold text-textcol">
                        Create a New Project
                    </h1>
                    <p class="mt-2 text-textcol2">
                        Set the project details, and add your first members.
                    </p>
                </div>

                <div class="p-10 space-y-10">

                    {{-- Project settings --}}
                    <div>
                        <form method="POST" action="{{ route('projects.store') }}" class="grid grid-cols-2 gap-8">
                            @csrf
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-medium text-textcol">Title</label>
                                    <x-forms.input class="w-[100%]" type="text" name="title" required />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-textcol">Description</label>
                                    <x-forms.textarea class="w-[100%]" name="description" rows="4">
                                    </x-forms.textarea>
                                </div>

                                <div class="flex justify-between">

                                    <div>
                                        <label class="block text-sm font-medium text-textcol">Visibility</label>
                                        <x-forms.select-dropdown name="is_public" :selected="0"
                                        :options="['0' => 'Private', '1' => 'Public']" />
                                    </div>
                                </div>

                                <div class="flex justify-between">
                                    <x-forms.button>
                                        <i class="fa-solid fa-save mr-2"></i>Launch Project
                                    </x-forms.button>

                                    <x-forms.button :secondary="true" href="{{ route('projects.index') }}">
                                        <i class="fa-solid fa-cancel mr-2"></i>Cancel
                                    </x-forms.button>
                                </div>
                            </div>
                            <div class="w-full max-w-md">
                                <div id="search-app">
                                    <label class="block text-sm font-medium text-textcol">Add New Members</label>
                                    <user-search :initial-users='@json([])'></user-search>

                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>