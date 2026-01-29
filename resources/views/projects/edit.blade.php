<x-layout>
    <x-slot:title>{{ $project->title }}</x-slot:title>
    <x-projects.edit-project-card :project="$project" :userStats="$userStats" />
</x-layout>