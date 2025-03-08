<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Ajouter un produit') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="mb-6 bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                        <h3 class="font-medium text-blue-300 mb-2">Informations du chantier</h3>
                        <p class="text-gray-300"><span class="font-medium">Client:</span> {{ $chantier->client->nom_complet }}</p>
                        <p class="text-gray-300"><span class="font-medium">Chantier:</span> {{ $chantier->nom }}</p>
                        <p class="text-gray-300"><span class="font-medium">Référence:</span> {{ $chantier->reference }}</p>
                    </div>

                    <form method="POST" action="{{ route('produits.store') }}">
                        @csrf
                        <input type="hidden" name="chantier_id" value="{{ $chantier->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Marque -->
                            <div>
                                <x-input-label for="marque" :value="__('Marque')" class="text-gray-300" />
                                <x-text-input id="marque" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="marque" :value="old('marque')" required />
                                <x-input-error :messages="$errors->get('marque')" class="mt-2" />
                            </div>

                            <!-- Modèle -->
                            <div>
                                <x-input-label for="modele" :value="__('Modèle')" class="text-gray-300" />
                                <x-text-input id="modele" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="modele" :value="old('modele')" required />
                                <x-input-error :messages="$errors->get('modele')" class="mt-2" />
                            </div>

                            <!-- Pitch -->
                            <div>
                                <x-input-label for="pitch" :value="__('Pitch (mm)')" class="text-gray-300" />
                                <x-text-input id="pitch" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="pitch" :value="old('pitch')" step="0.1" min="0.1" max="100" required />
                                <x-input-error :messages="$errors->get('pitch')" class="mt-2" />
                            </div>

                            <!-- Utilisation -->
                            <div>
                                <x-input-label for="utilisation" :value="__('Utilisation')" class="text-gray-300" />
                                <select id="utilisation" name="utilisation" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="indoor" {{ old('utilisation') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                                    <option value="outdoor" {{ old('utilisation') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                                </select>
                                <x-input-error :messages="$errors->get('utilisation')" class="mt-2" />
                            </div>

                            <!-- Électronique -->
                            <div>
                                <x-input-label for="electronique" :value="__('Électronique')" class="text-gray-300" />
                                <select id="electronique" name="electronique" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="nova" {{ old('electronique') == 'nova' ? 'selected' : '' }}>Nova</option>
                                    <option value="linsn" {{ old('electronique') == 'linsn' ? 'selected' : '' }}>Linsn</option>
                                    <option value="dbstar" {{ old('electronique') == 'dbstar' ? 'selected' : '' }}>DBstar</option>
                                    <option value="brompton" {{ old('electronique') == 'brompton' ? 'selected' : '' }}>Brompton</option>
                                    <option value="autre" {{ old('electronique') == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                <x-input-error :messages="$errors->get('electronique')" class="mt-2" />
                            </div>

                            <!-- Détail électronique (si autre) -->
                            <div id="electronique_detail_container" style="{{ old('electronique') == 'autre' ? '' : 'display: none;' }}">
                                <x-input-label for="electronique_detail" :value="__('Précisez l\'électronique')" class="text-gray-300" />
                                <x-text-input id="electronique_detail" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="electronique_detail" :value="old('electronique_detail')" />
                                <x-input-error :messages="$errors->get('electronique_detail')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('chantiers.show', $chantier) }}" class="btn-action btn-secondary mr-2">
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

    <!-- Script pour afficher/masquer le champ détail électronique -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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