@props([
    'state',
    'route',
    'model',
    'currentState'
])

@php
    $isActive = $currentState === $state;
    
    $baseClasses = "text-xs px-2 py-1 rounded transition-all duration-150";
    
    $stateClasses = match($state) {
        'non_commence' => "bg-gray-600 hover:bg-gray-500 text-white",
        'en_cours' => "bg-yellow-600 hover:bg-yellow-500 text-white",
        'termine' => "bg-green-600 hover:bg-green-500 text-white",
        'defaillant' => "bg-red-600 hover:bg-red-500 text-white",
        default => "bg-blue-600 hover:bg-blue-500 text-white"
    };
    
    $label = match($state) {
        'non_commence' => 'Non commencé',
        'en_cours' => 'En cours',
        'termine' => 'Terminé',
        'defaillant' => 'Défaillant',
        default => ucfirst(str_replace('_', ' ', $state))
    };
@endphp

@if(!$isActive)
    <form action="{{ $route }}" method="POST" class="inline">
        @csrf
        @method('PATCH')
        <input type="hidden" name="etat" value="{{ $state }}">
        <button type="submit" {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses}"]) }}>
            {{ $label }}
        </button>
    </form>
@endif