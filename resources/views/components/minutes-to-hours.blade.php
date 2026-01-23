@props(
    ['minutes' => $minutes]
)
@php
    // Time display from minutes to hours and minutes
    $time = $minutes ?? 0;
    if ($time >= 60) {
        $hours = floor($time / 60);
        $minutes = $time % 60;
        $timeDisplay = "{$hours}h {$minutes}min";
    } else {
        $timeDisplay = "{$time}min";
    }
@endphp
<div>{{ $timeDisplay }}</div>