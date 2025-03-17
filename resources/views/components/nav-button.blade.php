<a href="{{ isset($active) && $active ? '#' : $route }}" {{ $attributes->merge(['class' => isset($active) && $active ? 'sidebar-item active' : 'sidebar-item']) }}>
    @if (isset($icon))
        {!! $icon !!}
    @endif
    {{ $slot }}
</a>