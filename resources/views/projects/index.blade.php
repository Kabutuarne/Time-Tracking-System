<x-layout>
    <x-slot:title>Projects</x-slot:title>

    <div class="max-w-7xl mx-auto p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($projects as $project)
                <x-projects.card :project="$project" />
            @empty
                <p class="text-gray-500">No projects found.</p>
            @endforelse
        </div>
    </div>
</x-layout>