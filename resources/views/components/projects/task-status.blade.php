@props(['status' => 'archived'])

@php
    // Status colors and icons
    $colors = [
        'in_progress' => [
            'bg' => 'bg-yellow-500/10',
            'text' => 'text-yellow-400',
            'icon' => 'fa-solid fa-spinner',
            'label' => 'In Progress'
        ],
        'completed' => [
            'bg' => 'bg-green-500/10',
            'text' => 'text-green-400',
            'icon' => 'fa-solid fa-check-circle',
            'label' => 'Completed'
        ],
        'archived' => [
            'bg' => 'bg-gray-500/10',
            'text' => 'text-gray-400',
            'icon' => 'fa-solid fa-archive',
            'label' => 'Archived'
        ],
    ];

    // fallback
    $statusInfo = $colors[$status] ?? $colors['archived'];
@endphp

<div class="flex items-center gap-1 rounded-full {{ $statusInfo['bg'] }} px-2 py-0.5 shrink-0">
    <i class="{{ $statusInfo['icon'] }} {{ $statusInfo['text'] }} text-xs"></i>
    <span class="text-xs {{ $statusInfo['text'] }}">{{ $statusInfo['label'] }}</span>
</div>