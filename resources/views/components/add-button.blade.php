<a href="{{ $route }}" {{ $attributes->merge(['class' => 'btn-action btn-primary flex items-center']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
    {{ $slot ?? __('Ajouter') }}
</a>