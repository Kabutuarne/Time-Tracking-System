@props([
    'project',
    'type',
])
@php
    if ($type === 'members') {
        $icon = 'fa-solid fa-users';
        $count = $project->users_count + 1; // add owner
        $slot = 'Members';
        $color = 'text-primary';
        $bg = 'bg-primary/10';
    } elseif ($type === 'tasks') {
        $icon = 'fa-solid fa-tasks';
        $count = $project->tasks_count;
        $slot = 'Tasks';
        $color = 'text-secondary';
        $bg = 'bg-secondary/10';
    } elseif ($type === 'entries') {
        $icon = 'fas fa-pencil-alt';
        $count = $project->entries_count;
        $slot = 'Entries';
        $color = 'text-primary';
        $bg = 'bg-primary/10';
    } else {
        $icon = 'fa-solid fa-circle-info';
        $count = -1;
        $slot = 'Unknown';
        $color = 'text-textcol2';
        $bg = 'bg-slate-800/50';
    }
@endphp

<div class="rounded-lg {{ $bg }} p-3">
    <div class="flex items-center gap-2">
        <i class="{{ $icon }} {{ $color }} text-sm"></i>
        <span class="text-sm font-semibold {{ $color }}">{{ $count }}</span>
    </div>
    <p class="text-sm font-semibold {{ $color }}">{{ $slot }}</p>
</div>