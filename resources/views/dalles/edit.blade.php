<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la dalle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('dalles.update', $dalle) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Produit -->
                            <div>
                                <x-input-label for="produit_id" :value="__('Produit')" />
                                <select id="produit_id" name="produit_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @foreach($produits as $produit)
                                        <option value="{{ $produit->id }}" {{ $dalle->produit_id == $produit->id ? 'selected' : '' }}>
                                            {{ $produit->marque }} {{ $produit->modele }} ({{ $produit->chantier->nom }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('produit_id')" class="mt-2" />
                            </div>

                            <!-- Référence dalle -->
                            <div>
                                <x-input-label for="reference_dalle" :value="__('Référence (optionnel)')" />
                                <x-text-input id="reference_dalle" class="block mt-1 w-full" type="text" name="reference_dalle" :value="old('reference_dalle', $dalle->reference_dalle)" />
                                <p class="text-xs text-gray-500 mt-1">Référence de la dalle dans le système (ex: FC1-D1)</p>
                                <x-input-error :messages="$errors->get('reference_dalle')" class="mt-2" />
                            </div>
                            
                            <!-- Numéro dalle -->
                            <div>
                                <x-input-label for="numero_dalle" :value="__('Numéro dalle usine/client')" />
                                <x-text-input id="numero_dalle" class="block mt-1 w-full" type="text" name="numero_dalle" :value="old('numero_dalle', $dalle->numero_dalle)" />
                                <p class="text-xs text-gray-500 mt-1">Numéro d'usine ou attribué par le client pour la traçabilité</p>
                                <x-input-error :messages="$errors->get('numero_dalle')" class="mt-2" />
                            </div>

                            <!-- Largeur -->
                            <div>
                                <x-input-label for="largeur" :value="__('Largeur (mm)')" />
                                <x-text-input id="largeur" class="block mt-1 w-full" type="number" name="largeur" :value="old('largeur', $dalle->largeur)" step="0.1" min="1" required />
                                <x-input-error :messages="$errors->get('largeur')" class="mt-2" />
                            </div>

                            <!-- Hauteur -->
                            <div>
                                <x-input-label for="hauteur" :value="__('Hauteur (mm)')" />
                                <x-text-input id="hauteur" class="block mt-1 w-full" type="number" name="hauteur" :value="old('hauteur', $dalle->hauteur)" step="0.1" min="1" required />
                                <x-input-error :messages="$errors->get('hauteur')" class="mt-2" />
                            </div>

                            <!-- Nombre de modules -->
                            <div>
                                <x-input-label for="nb_modules" :value="__('Nombre de modules')" />
                                <x-text-input id="nb_modules" class="block mt-1 w-full" type="number" name="nb_modules" :value="old('nb_modules', $dalle->nb_modules)" min="1" required />
                                <x-input-error :messages="$errors->get('nb_modules')" class="mt-2" />
                            </div>

                            <!-- Alimentation -->
                            <div>
                                <x-input-label for="alimentation" :value="__('Alimentation')" />
                                <x-text-input id="alimentation" class="block mt-1 w-full" type="text" name="alimentation" :value="old('alimentation', $dalle->alimentation)" required />
                                <x-input-error :messages="$errors->get('alimentation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('dalles.show', $dalle) }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 mr-2">
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button>
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
