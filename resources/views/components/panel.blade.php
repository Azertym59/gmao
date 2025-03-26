@props(['title' => null, 'icon' => null, 'color' => 'primary'])

@php
    $colorClasses = [
        'primary' => 'text-accent-primary border-accent-primary/20 bg-accent-primary/5',
        'secondary' => 'text-accent-secondary border-accent-secondary/20 bg-accent-secondary/5',
        'success' => 'text-success border-success/20 bg-success/5',
        'warning' => 'text-warning border-warning/20 bg-warning/5',
        'danger' => 'text-error border-error/20 bg-error/5',
        'info' => 'text-accent-blue border-accent-blue/20 bg-accent-blue/5',
    ][$color] ?? 'text-accent-primary border-accent-primary/20 bg-accent-primary/5';
@endphp

<div {{ $attributes->merge(['class' => 'glassmorphism p-6 mb-6']) }}>
    @if($title)
    <div class="flex items-center mb-4">
        @if($icon)
        <div class="mr-3 p-2.5 rounded-lg {{ $colorClasses }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
            </svg>
        </div>
        @endif
        <h3 class="text-lg font-medium text-white">{{ $title }}</h3>
    </div>
    @endif
    
    <div>
        {{ $slot }}
    </div>
</div>