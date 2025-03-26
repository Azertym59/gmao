@props(['tag' => 'button', 'href' => '#', 'type' => 'button'])

@if($tag === 'a')
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn-modern btn-success']) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => $type, 'class' => 'btn-modern btn-success']) }}>
    {{ $slot }}
</button>
@endif