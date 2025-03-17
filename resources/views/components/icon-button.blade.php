@props([
    'tag' => 'button', 
    'href' => '#', 
    'type' => 'primary',
    'tooltip' => '',
    'tooltipPosition' => 'bottom',
    'size' => 'md'
])

@php
    $baseClass = 'btn-icon';
    
    // Définir la classe de taille
    $sizeClass = match($size) {
        'xs' => 'btn-icon-xs',
        'sm' => 'btn-icon-sm',
        'lg' => 'btn-icon-lg',
        'xl' => 'btn-icon-xl',
        default => 'btn-icon-md'
    };
    
    // Définir la classe de type
    $typeClass = match($type) {
        'secondary' => 'btn-secondary',
        'success' => 'btn-success',
        'danger' => 'btn-danger',
        'info' => 'btn-info',
        'outline' => 'btn-outline',
        'action' => 'btn-action',
        default => 'btn-primary'
    };
    
    // Classes pour la position du tooltip
    $tooltipClass = $tooltip ? 'tooltip tooltip-' . $tooltipPosition : '';
    
    // Classes combinées
    $classes = "{$baseClass} {$sizeClass} {$typeClass} {$tooltipClass}";
@endphp

@if($tag === 'a')
<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if($tooltip) data-tooltip="{{ $tooltip }}" @endif>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['class' => $classes]) }} @if($tooltip) data-tooltip="{{ $tooltip }}" @endif>
    {{ $slot }}
</button>
@endif