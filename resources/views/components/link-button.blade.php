@props(['tag' => 'button', 'href' => '#'])

@if($tag === 'a')
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn-link']) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-link']) }}>
    {{ $slot }}
</button>
@endif