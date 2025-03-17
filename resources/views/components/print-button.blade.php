@props([
    'route',
    'type' => 'default',
    'buttonStyle' => '',
    'tag' => 'button',
    'onclick' => '',
    'size' => 'md'
])

@php
    $baseClass = 'btn inline-flex items-center justify-center shadow-md transition-all duration-300 hover:scale-105';
    
    // Classes selon le type
    $typeClass = match($type) {
        'qrcode' => 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white border border-blue-700 hover:from-blue-700 hover:to-indigo-700',
        'brother' => 'text-white border border-fuchsia-700',
        'zebra' => 'bg-gradient-to-r from-gray-800 to-black text-white border border-gray-700 hover:from-gray-700 hover:to-gray-900',
        'default' => 'bg-gradient-to-r from-gray-600 to-gray-800 text-white border border-gray-700 hover:from-gray-500 hover:to-gray-700',
    };
    
    // Style inline pour Brother (couleur spécifique)
    $inlineStyle = $type === 'brother' ? 'background: linear-gradient(135deg, #b01e8e 0%, #d027a6 100%);' : '';
    
    // Taille
    $sizeClass = match($size) {
        'sm' => 'px-3 py-1 text-xs rounded-md',
        'lg' => 'px-5 py-3 text-base rounded-xl',
        default => 'px-4 py-2 text-sm rounded-lg',
    };
    
    $classes = "{$baseClass} {$typeClass} {$sizeClass} {$buttonStyle}";
@endphp

@if($tag === 'a')
    <a href="{{ $route }}" {{ $attributes->merge(['class' => $classes]) }} style="{{ $inlineStyle }}">
        {{ $slot }}
    </a>
@else
    <button onclick="{{ $onclick }}" {{ $attributes->merge(['class' => $classes]) }} style="{{ $inlineStyle }}">
        {{ $slot }}
    </button>
@endif