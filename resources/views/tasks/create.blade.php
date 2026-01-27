<x-layout>
    <x-slot:title>{{ $project->title }}</x-slot:title>
    <x-tasks.create-task-card :project="$project" />
</x-layout>