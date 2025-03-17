@props(['tag' => 'button', 'href' => '#'])

@if($tag === 'a')
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn-outline']) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-outline']) }}>
    {{ $slot }}
</button>
@endif