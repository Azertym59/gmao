@props(['cols' => '2'])

<div {{ $attributes->merge(['class' => "grid grid-cols-1 md:grid-cols-{$cols} gap-6"]) }}>
    {{ $slot }}
</div>