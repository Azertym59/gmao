<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Ajouter une variante') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="mb-6 bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                        <h3 class="font-medium text-blue-300 mb-2">Informations du produit parent</h3>
                        <p class="text-gray-300"><span class="font-medium">Marque:</span> {{ $produit->marque }}</p>
                        <p class="text-gray-300"><span class="font-medium">Modèle:</span> {{ $produit->modele }}</p>
                        <p class="text-gray-300"><span class="font-medium">Pitch:</span> {{ $produit->pitch }} mm</p>
                        <p class="text-gray-300"><span class="font-medium">Utilisation:</span> {{ $produit->utilisation === 'indoor' ? 'Indoor' : 'Outdoor' }}</p>
                        <p class="text-gray-300"><span class="font-medium">Électronique:</span> {{ $produit->electronique === 'autre' ? $produit->electronique_detail : ucfirst($produit->electronique) }}</p>
                    </div>

                    <form method="POST" action="{{ route('produits.store-variante', $produit) }}">
                        @csrf

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-blue-300 mb-4">Caractéristiques spécifiques de la variante</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nom de la variante -->
                                <div>
                                    <x-input-label for="variante_nom" :value="__('Nom de la variante')" class="text-gray-300" />
                                    <x-text-input id="variante_nom" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="variante_nom" :value="old('variante_nom')" placeholder="ex: Standard" required />
                                    <x-input-error :messages="$errors->get('variante_nom')" class="mt-2" />
                                </div>

                                <!-- Carte de réception -->
                                <div>
                                    <x-input-label for="carte_reception" :value="__('Carte de réception')" class="text-gray-300" />
                                    <x-text-input id="carte_reception" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="carte_reception" :value="old('carte_reception')" placeholder="ex: Novastar Taurus" required />
                                    <x-input-error :messages="$errors->get('carte_reception')" class="mt-2" />
                                </div>

                                <!-- Hub -->
                                <div>
                                    <x-input-label for="hub" :value="__('Hub')" class="text-gray-300" />
                                    <x-text-input id="hub" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="hub" :value="old('hub')" placeholder="ex: Multimédia" required />
                                    <x-input-error :messages="$errors->get('hub')" class="mt-2" />
                                </div>

                                <!-- Bain de couleur -->
                                <div>
                                    <x-input-label for="bain_couleur" :value="__('Bain de couleur')" class="text-gray-300" />
                                    <x-text-input id="bain_couleur" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="bain_couleur" :value="old('bain_couleur')" placeholder="ex: RGB" required />
                                    <x-input-error :messages="$errors->get('bain_couleur')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('produits.show', $produit) }}" class="btn-action btn-secondary mr-2">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="btn-action btn-primary">
                                {{ __('Enregistrer') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>