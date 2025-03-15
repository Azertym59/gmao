<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Test de Formulaire - Sélection du Catalogue') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-xl sm:rounded-lg p-6">
            
                @if(session('success'))
                <div class="mb-6 bg-green-900/30 border border-green-500/30 p-4 rounded-xl text-green-300">
                    {{ session('success') }}
                </div>
                @endif
                
                <h3 class="text-lg font-semibold text-white mb-6">Formulaire de test minimaliste</h3>
                
                <form method="POST" action="{{ route('test.catalogue.form.submit') }}" id="test-form">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="catalogue_id" class="block text-sm font-medium text-gray-300">Sélectionner un produit du catalogue</label>
                        <select id="catalogue_id" name="catalogue_id" class="mt-1 block w-full py-2 px-3 border border-gray-600 bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-white sm:text-sm">
                            <option value="">-- Sélectionner un produit --</option>
                            @foreach($produitsCatalogue as $produit)
                                <option value="{{ $produit->id }}">{{ $produit->marque }} {{ $produit->modele }}</option>
                            @endforeach
                        </select>
                        @error('catalogue_id')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="hidden">
                        <!-- Champs cachés qui existent dans le formulaire principal mais ne sont pas requis ici -->
                        <input type="text" name="marque" id="marque">
                        <input type="text" name="modele" id="modele">
                        <input type="number" name="pitch" id="pitch">
                        <input type="hidden" name="from_catalogue" value="1">
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" id="manual-submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Soumettre
                        </button>
                    </div>
                </form>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const submitButton = document.getElementById('manual-submit');
                        const catalogueForm = document.getElementById('test-form');
                        
                        submitButton.addEventListener('click', function() {
                            // Soumettre manuellement le formulaire
                            catalogueForm.submit();
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>