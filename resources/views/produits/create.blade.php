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

                        <!-- Informations de base du produit -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-blue-300 mb-4">Informations générales du produit</h3>
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
                        </div>

                        <!-- Caractéristiques spécifiques du produit -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-blue-300 mb-4">Caractéristiques spécifiques</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Carte de réception -->
                                <div>
                                    <x-input-label for="carte_reception" :value="__('Carte de réception')" class="text-gray-300" />
                                    <x-text-input id="carte_reception" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="carte_reception" :value="old('carte_reception')" placeholder="ex: Novastar Taurus" />
                                    <x-input-error :messages="$errors->get('carte_reception')" class="mt-2" />
                                </div>

                                <!-- Hub -->
                                <div>
                                    <x-input-label for="hub" :value="__('Hub')" class="text-gray-300" />
                                    <x-text-input id="hub" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="hub" :value="old('hub')" placeholder="ex: Multimédia" />
                                    <x-input-error :messages="$errors->get('hub')" class="mt-2" />
                                </div>

                                <!-- Bain de couleur -->
                                <div>
                                    <x-input-label for="bain_couleur" :value="__('Bain de couleur')" class="text-gray-300" />
                                    <x-text-input id="bain_couleur" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="bain_couleur" :value="old('bain_couleur')" placeholder="ex: RGB" />
                                    <x-input-error :messages="$errors->get('bain_couleur')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Section des variantes -->
                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <input id="create_variantes" type="checkbox" name="create_variantes" value="1" {{ old('create_variantes') ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-800 border-gray-600 rounded focus:ring-blue-500">
                                <label for="create_variantes" class="ml-2 text-lg font-medium text-blue-300">Ajouter des variantes pour ce produit</label>
                            </div>
                            
                            <div id="variantes_container" class="border border-blue-500/30 rounded-lg p-4 bg-blue-900/10" style="{{ old('create_variantes') ? '' : 'display: none;' }}">
                                <p class="text-gray-300 mb-3">Ajoutez des variantes avec différentes cartes de réception, hubs et bains de couleur.</p>
                                
                                <div id="variantes_list">
                                    <!-- Les variantes seront ajoutées ici dynamiquement -->
                                    @if(old('variantes'))
                                        @foreach(old('variantes') as $key => $variante)
                                            <div class="variante-item mb-4 p-3 border border-gray-700 rounded-lg">
                                                <div class="flex justify-between items-center mb-3">
                                                    <h4 class="text-white font-medium">Variante #{{ $key + 1 }}</h4>
                                                    <button type="button" class="remove-variante text-red-400 hover:text-red-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-300">Nom de la variante</label>
                                                        <input type="text" name="variantes[{{ $key }}][variante_nom]" value="{{ $variante['variante_nom'] ?? '' }}" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" placeholder="ex: Standard" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-300">Carte de réception</label>
                                                        <input type="text" name="variantes[{{ $key }}][carte_reception]" value="{{ $variante['carte_reception'] ?? '' }}" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" placeholder="ex: Novastar Taurus" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-300">Hub</label>
                                                        <input type="text" name="variantes[{{ $key }}][hub]" value="{{ $variante['hub'] ?? '' }}" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" placeholder="ex: Multimédia" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-300">Bain de couleur</label>
                                                        <input type="text" name="variantes[{{ $key }}][bain_couleur]" value="{{ $variante['bain_couleur'] ?? '' }}" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" placeholder="ex: RGB" required>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                
                                <button type="button" id="add_variante" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                    </svg>
                                    Ajouter une variante
                                </button>
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
            
            // Gestion de l'affichage des variantes
            const createVariantesCheckbox = document.getElementById('create_variantes');
            const variantesContainer = document.getElementById('variantes_container');
            
            createVariantesCheckbox.addEventListener('change', function() {
                variantesContainer.style.display = this.checked ? 'block' : 'none';
            });
            
            // Gestion de l'ajout de variantes
            const addVarianteButton = document.getElementById('add_variante');
            const variantesList = document.getElementById('variantes_list');
            let varianteCount = variantesList.querySelectorAll('.variante-item').length;
            
            addVarianteButton.addEventListener('click', function() {
                // Créer un nouvel élément variante
                const varianteItem = document.createElement('div');
                varianteItem.className = 'variante-item mb-4 p-3 border border-gray-700 rounded-lg';
                varianteItem.innerHTML = `
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="text-white font-medium">Variante #${varianteCount + 1}</h4>
                        <button type="button" class="remove-variante text-red-400 hover:text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Nom de la variante</label>
                            <input type="text" name="variantes[${varianteCount}][variante_nom]" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" placeholder="ex: Standard" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Carte de réception</label>
                            <input type="text" name="variantes[${varianteCount}][carte_reception]" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" placeholder="ex: Novastar Taurus" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Hub</label>
                            <input type="text" name="variantes[${varianteCount}][hub]" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" placeholder="ex: Multimédia" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Bain de couleur</label>
                            <input type="text" name="variantes[${varianteCount}][bain_couleur]" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" placeholder="ex: RGB" required>
                        </div>
                    </div>
                `;
                
                // Ajouter au DOM
                variantesList.appendChild(varianteItem);
                varianteCount++;
                
                // Ajouter l'écouteur d'événements pour le bouton de suppression
                const removeButton = varianteItem.querySelector('.remove-variante');
                removeButton.addEventListener('click', function() {
                    varianteItem.remove();
                    // Mettre à jour les numéros des variantes
                    updateVarianteNumbers();
                });
            });
            
            // Fonction pour mettre à jour les numéros des variantes
            function updateVarianteNumbers() {
                const variantes = variantesList.querySelectorAll('.variante-item');
                variantes.forEach((variante, index) => {
                    // Mettre à jour le titre
                    variante.querySelector('h4').textContent = `Variante #${index + 1}`;
                    
                    // Mettre à jour les noms des champs
                    const inputs = variante.querySelectorAll('input');
                    inputs.forEach(input => {
                        const nameParts = input.name.split('[');
                        const fieldName = nameParts[2].split(']')[0];
                        input.name = `variantes[${index}][${fieldName}]`;
                    });
                });
            }
            
            // Ajouter les écouteurs d'événements pour les boutons de suppression existants
            const removeButtons = document.querySelectorAll('.remove-variante');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.variante-item').remove();
                    updateVarianteNumbers();
                });
            });
        });
    </script>
</x-app-layout>