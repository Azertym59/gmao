@props(['tag' => 'button', 'href' => '#'])

@if($tag === 'a')
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn-info']) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-info']) }}>
    {{ $slot }}
</button>
@endif