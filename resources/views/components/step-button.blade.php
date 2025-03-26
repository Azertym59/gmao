@props(['number', 'active' => false, 'text' => ''])

<div class="relative flex flex-col items-center">
    <div class="step-button {{ $active ? 'active' : 'inactive' }}">
        <span class="relative z-10 {{ $active ? 'font-bold' : '' }}">{{ $number }}</span>
    </div>
    <div class="text-center mt-1 w-32">
        <span class="step-text {{ $active ? 'text-accent-primary font-semibold' : 'text-gray-400' }}">{{ $text }}</span>
    </div>
</div>