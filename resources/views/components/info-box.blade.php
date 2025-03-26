@props(['type' => 'info', 'title' => ''])

@php
    $typeClasses = [
        'info' => 'bg-accent-blue/10 border-accent-blue/20 text-accent-blue',
        'success' => 'bg-accent-green/10 border-accent-green/20 text-accent-green',
        'warning' => 'bg-warning/10 border-warning/20 text-warning',
        'error' => 'bg-error/10 border-error/20 text-error',
    ][$type] ?? 'bg-accent-blue/10 border-accent-blue/20 text-accent-blue';
    
    $icon = [
        'info' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'success' => 'M5 13l4 4L19 7',
        'warning' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        'error' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ][$type] ?? 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
@endphp

<div {{ $attributes->merge(['class' => "p-4 rounded-xl border $typeClasses"]) }}>
    <div class="flex items-start">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
        </svg>
        <div>
            @if($title)
            <h4 class="font-medium mb-1">{{ $title }}</h4>
            @endif
            <div class="text-sm">{{ $slot }}</div>
        </div>
    </div>
</div>