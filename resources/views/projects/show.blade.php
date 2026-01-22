<x-layout>
    <x-slot:title>Project</x-slot:title>
    <div class="max-w-7xl mx-auto p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-projects.card :project="$project" />
        </div>
    </div>
</x-layout>