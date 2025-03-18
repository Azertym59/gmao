@props(['tag' => 'button', 'href' => '#', 'color' => 'primary'])

@php
    $buttonClass = 'btn-action btn-' . $color;
@endphp

@if($tag === 'a')
<a href="{{ $href }}" {{ $attributes->merge(['class' => $buttonClass]) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button', 'class' => $buttonClass]) }}>
    {{ $slot }}
</button>
@endif