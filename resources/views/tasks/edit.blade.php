<x-layout>
    <x-slot:title>{{ $task->title }}</x-slot:title>
    <x-tasks.edit-task-card :task="$task" :taskStats="$taskStats" :entries="$entries" />
</x-layout>