@props(['tag' => 'button', 'href' => '#'])

@if($tag === 'a')
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn-action']) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-action']) }}>
    {{ $slot }}
</button>
@endif