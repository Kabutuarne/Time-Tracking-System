<x-layout>
    <x-slot:title>{{ $project->title }}</x-slot:title>
    <x-projects.full-card :project="$project" :userStats="$userStats" :projectStats="$projectStats" :entries="$entries"
        :tasks="$tasks" />
</x-layout>