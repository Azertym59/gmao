@props(['tag' => 'button', 'href' => '#'])

@if($tag === 'a')
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn-success']) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-success']) }}>
    {{ $slot }}
</button>
@endif