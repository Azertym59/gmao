<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter une dalle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6 bg-blue-50 p-4 rounded-lg">
                        <h3 class="font-medium text-blue-800">Informations du produit</h3>
                        <p><span class="font-medium">Produit:</span> {{ $produit->marque }} {{ $produit->modele }}</p>
                        <p><span class="font-medium">Chantier:</span> {{ $produit->chantier->nom }}</p>
                        <p><span class="font-medium">Client:</span> {{ $produit->chantier->client->nom_complet }}</p>
                    </div>

                    <form method="POST" action="{{ route('dalles.store') }}">
                        @csrf
                        <input type="hidden" name="produit_id" value="{{ $produit->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Largeur -->
                            <div>
                                <x-input-label for="largeur" :value="__('Largeur (mm)')" />
                                <x-text-input id="largeur" class="block mt-1 w-full" type="number" name="largeur" :value="old('largeur')" step="0.1" min="1" required />
                                <x-input-error :messages="$errors->get('largeur')" class="mt-2" />
                            </div>

                            <!-- Hauteur -->
                            <div>
                                <x-input-label for="hauteur" :value="__('Hauteur (mm)')" />
                                <x-text-input id="hauteur" class="block mt-1 w-full" type="number" name="hauteur" :value="old('hauteur')" step="0.1" min="1" required />
                                <x-input-error :messages="$errors->get('hauteur')" class="mt-2" />
                            </div>

                            <!-- Nombre de modules -->
                            <div>
                                <x-input-label for="nb_modules" :value="__('Nombre de modules')" />
                                <x-text-input id="nb_modules" class="block mt-1 w-full" type="number" name="nb_modules" :value="old('nb_modules', 1)" min="1" required />
                                <x-input-error :messages="$errors->get('nb_modules')" class="mt-2" />
                            </div>

                            <!-- Alimentation -->
                            <div>
                                <x-input-label for="alimentation" :value="__('Alimentation')" />
                                <x-text-input id="alimentation" class="block mt-1 w-full" type="text" name="alimentation" :value="old('alimentation')" required />
                                <x-input-error :messages="$errors->get('alimentation')" class="mt-2" />
                            </div>

                            <!-- Référence dalle -->
                            <div>
                                <x-input-label for="reference_dalle" :value="__('Référence (optionnel)')" />
                                <x-text-input id="reference_dalle" class="block mt-1 w-full" type="text" name="reference_dalle" :value="old('reference_dalle')" />
                                <x-input-error :messages="$errors->get('reference_dalle')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('produits.show', $produit) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button>
                                {{ __('Enregistrer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
