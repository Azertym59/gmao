<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Modifier le produit') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="mb-6 bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                        <h3 class="font-medium text-blue-300 mb-2">Informations du chantier</h3>
                        <p class="text-gray-300"><span class="font-medium">Client:</span> {{ $produit->chantier->client->nom_complet }}</p>
                        <p class="text-gray-300"><span class="font-medium">Chantier:</span> {{ $produit->chantier->nom }}</p>
                        <p class="text-gray-300"><span class="font-medium">Référence:</span> {{ $produit->chantier->reference }}</p>
                    </div>

                    <form method="POST" action="{{ route('produits.update', $produit) }}">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="chantier_id" value="{{ $produit->chantier_id }}">

                        <!-- Informations de base du produit -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-blue-300 mb-4">Informations générales du produit</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Marque -->
                                <div>
                                    <x-input-label for="marque" :value="__('Marque')" class="text-gray-300" />
                                    <x-text-input id="marque" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="marque" :value="old('marque', $produit->marque)" required />
                                    <x-input-error :messages="$errors->get('marque')" class="mt-2" />
                                </div>

                                <!-- Modèle -->
                                <div>
                                    <x-input-label for="modele" :value="__('Modèle')" class="text-gray-300" />
                                    <x-text-input id="modele" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="modele" :value="old('modele', $produit->modele)" required />
                                    <x-input-error :messages="$errors->get('modele')" class="mt-2" />
                                </div>

                                <!-- Pitch -->
                                <div>
                                    <x-input-label for="pitch" :value="__('Pitch (mm)')" class="text-gray-300" />
                                    <x-text-input id="pitch" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="pitch" :value="old('pitch', $produit->pitch)" step="0.1" min="0.1" max="100" required />
                                    <x-input-error :messages="$errors->get('pitch')" class="mt-2" />
                                </div>

                                <!-- Utilisation -->
                                <div>
                                    <x-input-label for="utilisation" :value="__('Utilisation')" class="text-gray-300" />
                                    <select id="utilisation" name="utilisation" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="indoor" {{ old('utilisation', $produit->utilisation) == 'indoor' ? 'selected' : '' }}>Indoor</option>
                                        <option value="outdoor" {{ old('utilisation', $produit->utilisation) == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('utilisation')" class="mt-2" />
                                </div>

                                <!-- Électronique -->
                                <div>
                                    <x-input-label for="electronique" :value="__('Électronique')" class="text-gray-300" />
                                    <select id="electronique" name="electronique" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                        <option value="nova" {{ old('electronique', $produit->electronique) == 'nova' ? 'selected' : '' }}>Nova</option>
                                        <option value="linsn" {{ old('electronique', $produit->electronique) == 'linsn' ? 'selected' : '' }}>Linsn</option>
                                        <option value="dbstar" {{ old('electronique', $produit->electronique) == 'dbstar' ? 'selected' : '' }}>DBstar</option>
                                        <option value="brompton" {{ old('electronique', $produit->electronique) == 'brompton' ? 'selected' : '' }}>Brompton</option>
                                        <option value="autre" {{ old('electronique', $produit->electronique) == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('electronique')" class="mt-2" />
                                </div>

                                <!-- Détail électronique (si autre) -->
                                <div id="electronique_detail_container" style="{{ old('electronique', $produit->electronique) == 'autre' ? '' : 'display: none;' }}">
                                    <x-input-label for="electronique_detail" :value="__('Précisez l\'électronique')" class="text-gray-300" />
                                    <x-text-input id="electronique_detail" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="electronique_detail" :value="old('electronique_detail', $produit->electronique_detail)" />
                                    <x-input-error :messages="$errors->get('electronique_detail')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Caractéristiques spécifiques du produit -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-blue-300 mb-4">Caractéristiques spécifiques</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Carte de réception -->
                                <div>
                                    <x-input-label for="carte_reception" :value="__('Carte de réception')" class="text-gray-300" />
                                    <x-text-input id="carte_reception" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="carte_reception" :value="old('carte_reception', $produit->carte_reception)" placeholder="ex: Novastar Taurus TB6" />
                                    <p class="text-xs text-gray-400 mt-1">Format conseillé: Marque Modèle (Référence)</p>
                                    <x-input-error :messages="$errors->get('carte_reception')" class="mt-2" />
                                </div>

                                <!-- Hub -->
                                <div>
                                    <x-input-label for="hub" :value="__('Hub')" class="text-gray-300" />
                                    <x-text-input id="hub" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="hub" :value="old('hub', $produit->hub)" placeholder="ex: Multimédia" />
                                    <x-input-error :messages="$errors->get('hub')" class="mt-2" />
                                </div>

                                <!-- Bain de couleur -->
                                <div>
                                    <x-input-label for="bain_couleur" :value="__('Bain de couleur')" class="text-gray-300" />
                                    <x-text-input id="bain_couleur" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="bain_couleur" :value="old('bain_couleur', $produit->bain_couleur)" placeholder="ex: RGB" />
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

    <!-- Scripts pour la gestion du formulaire -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du champ détail électronique
            const electroniqueSelect = document.getElementById('electronique');
            const electroniqueDetailContainer = document.getElementById('electronique_detail_container');
            
            electroniqueSelect.addEventListener('change', function() {
                if (this.value === 'autre') {
                    electroniqueDetailContainer.style.display = 'block';
                } else {
                    electroniqueDetailContainer.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>