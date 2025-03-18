<form method="POST" action="{{ $route }}" class="inline" onsubmit="return confirm('{{ $confirmText ?? __('Êtes-vous sûr de vouloir supprimer cet élément ?') }}')">
    @csrf
    @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'btn-action btn-danger flex items-center']) }}>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        {{ $slot ?? __('Supprimer') }}
    </button>
</form>