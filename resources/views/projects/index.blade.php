<x-layout>
    <x-slot:title>Projects</x-slot:title>

    <div class="max-w-7xl mx-auto p-6">
        <h2 class="text-2xl font-bold text-textcol py-4">Find public projects for inspiration</h2>
        @livewire('project-project', ['projects' => $projects])
    </div>
</x-layout>