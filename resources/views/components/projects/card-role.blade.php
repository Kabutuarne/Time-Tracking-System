
@props([
    'role'
])
@if ($role != null)
@php
    

    //Role colors
    $roleKey = strtolower($role);
    $roleColors = [
        'owner' => [
            'tagBg' => 'bg-indigo-500/10',
            'tagText' => 'text-indigo-400',
        ],
        'member' => [
            'tagBg' => 'bg-green-500/10',
            'tagText' => 'text-green-400',
        ],
        'manager' => [
            'tagBg' => 'bg-yellow-500/10',
            'tagText' => 'text-yellow-400',
        ],
    ];

    $colors = $roleColors[$roleKey] ?? $roleColors['member'];
@endphp
    <div class="flex items-center gap-1 rounded-full {{ $colors['tagBg'] }} px-2.5 py-1 shrink-0">
            <span class="text-xs font-medium {{ $colors['tagText'] }} capitalize">{{ $role }} of</span>
        </div>
@endif