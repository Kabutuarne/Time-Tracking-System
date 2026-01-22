@props([
    'project' => $project,
    'type' => $type,
])
@php
    if ($type === 'members') {
        $icon = 'fa-solid fa-users';
        $count = $project->users->count();
        $slot = 'Members';
    } elseif ($type === 'tasks') {
        $icon = 'fa-solid fa-tasks';
        $count = $project->tasks->count();
        $slot = 'Tasks';
    } elseif ($type === 'entries') {
        $icon = 'fa-solid fa-clock';
        $count = $project->tasks->flatMap->entries->count();
        $slot = 'Entries';
    } else {
        $icon = 'fa-solid fa-circle-info';
        $count = -1;
        $slot = 'Unknown';
    }
@endphp

<div class="rounded-xl bg-dark p-4">
    <div class="flex items-center gap-2">
        <div stroke="currentColor" viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-textcol">
            <i class="{{ $icon }}"></i>
        </div>
        <span class="text-sm font-medium text-textcol">{{ $count }}</span>
    </div>
    <p class="mt-1 text-xs text-textcol2">{{ $slot }}</p>
</div>