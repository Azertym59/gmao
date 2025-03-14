@props([
    'label' => 'Rechercher un client',
    'placeholder' => 'Recherchez par nom, prénom, société, email...',
    'required' => false
])

<div x-data="clientAutocomplete()" {{ $attributes->merge(['class' => 'mb-4']) }}>
    <label class="block text-sm font-medium text-gray-300 mb-1">{{ $label }}</label>
    <div class="relative">
        <input 
            type="text" 
            x-model="query"
            placeholder="{{ $placeholder }}" 
            class="block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
            {{ $required ? 'required' : '' }}
        >
        <!-- Champ caché pour stocker l'ID du client sélectionné -->
        <input type="hidden" name="client_id" id="client_id">
        
        <!-- Indicateur de chargement -->
        <div x-show="isLoading" class="absolute right-3 top-3">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
    
    <!-- Résultats de la recherche -->
    <div x-show="showResults" class="absolute mt-1 w-full max-w-lg bg-gray-800 border border-gray-700 rounded-md shadow-lg z-50 max-h-60 overflow-y-auto">
        <ul class="py-1">
            <template x-for="client in results" :key="client.id">
                <li @click="selectClient(client)" class="px-4 py-2 hover:bg-gray-700 cursor-pointer">
                    <div x-text="client.display_text" class="font-medium"></div>
                    <div class="flex text-xs text-gray-400 mt-1">
                        <span x-text="client.email"></span>
                        <span x-show="client.telephone" class="ml-2" x-text="'| ' + client.telephone"></span>
                    </div>
                </li>
            </template>
        </ul>
    </div>
    
    <!-- Client sélectionné -->
    <div x-show="selectedClient" class="mt-2 p-3 bg-gray-700/50 border border-gray-600 rounded-md flex justify-between items-center">
        <div>
            <div class="font-medium text-white" x-text="selectedClient.display_text"></div>
            <div class="text-xs text-gray-400 mt-1">
                <span x-text="selectedClient.email"></span>
            </div>
        </div>
        <button @click="resetSelection" type="button" class="text-red-400 hover:text-red-300 text-sm">
            Changer
        </button>
    </div>
</div>