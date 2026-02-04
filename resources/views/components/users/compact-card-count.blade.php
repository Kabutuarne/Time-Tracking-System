@props([
    'project',
    'type',
])
@php
    if ($type === 'members') {
        $icon = 'fa-solid fa-users';
        $count = $project->users_count + 1;
        $label = 'Members';
        $color = 'text-primary';
        $bg = 'bg-primary/10';

    } elseif ($type === 'tasks') {
        $icon = 'fa-solid fa-tasks';
        $count = $project->tasks_count;
        $label = 'Total Tasks';
        $color = 'text-secondary';
        $bg = 'bg-secondary/10';

    } elseif ($type === 'active-tasks') {
        $icon = 'fa-solid fa-bolt';
        $count = $project->tasks
            ->where('status', 'in_progress')
            ->count();
        $label = 'Active Tasks';
        $color = 'text-primary';
        $bg = 'text-primary/10';

    } else {
        $icon = 'fa-solid fa-circle-info';
        $count = '-';
        $label = 'Unknown';
        $color = 'text-textcol2';
        $bg = 'bg-slate-800/50';
    }
@endphp

<div class="rounded-md {{ $bg }} px-3 py-2 min-w-[90px]">
    <div class="flex items-center gap-2">
        <i class="{{ $icon }} {{ $color }} text-xs"></i>
        <span class="text-sm font-semibold {{ $color }}">
            {{ $count }}
        </span>
    </div>
    <p class="text-[10px] font-semibold tracking-wide {{ $color }}">
        {{ $label }}
    </p>
</div>
