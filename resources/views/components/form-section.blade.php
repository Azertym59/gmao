@props(['title' => '', 'subtitle' => '', 'icon' => null])

<div {{ $attributes->merge(['class' => 'form-section bg-card-bg border border-border-light rounded-xl mb-6 overflow-hidden']) }}>
    @if($title)
    <div class="border-b border-border-light p-6">
        <h3 class="text-xl font-medium text-white flex items-center">
            @if($icon)
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
            </svg>
            @endif
            {{ $title }}
        </h3>
        @if($subtitle)
        <p class="mt-1 text-sm text-text-secondary">{{ $subtitle }}</p>
        @endif
    </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
</div>