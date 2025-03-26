<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-white leading-tight">
            {{ __('Modifier un modèle d\'étiquette') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">Modification du modèle d'étiquette</h3>
                        <a href="{{ route('admin.etiquettes.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-150 ease-in-out flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Retour
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-500/30 border border-red-500/50 text-red-400 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.etiquettes.update', $template) }}" method="POST" id="templateForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="name" :value="__('Nom du modèle')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                    :value="old('name', $template->name)" required autofocus />
                            </div>
                            
                            <div>
                                <x-input-label for="type" :value="__('Type d\'étiquette')" />
                                <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-white focus:border-accent-yellow focus:ring focus:ring-accent-yellow/30 transition-all">
                                    <option value="chantier" {{ old('type', $template->type) == 'chantier' ? 'selected' : '' }}>Chantier</option>
                                    <option value="dalle" {{ old('type', $template->type) == 'dalle' ? 'selected' : '' }}>Dalle</option>
                                    <option value="module" {{ old('type', $template->type) == 'module' ? 'selected' : '' }}>Module</option>
                                </select>
                            </div>
                            
                            <div>
                                <x-input-label for="width" :value="__('Largeur (mm)')" />
                                <x-text-input id="width" name="width" type="number" step="0.1" class="mt-1 block w-full" 
                                    :value="old('width', $template->width)" required />
                            </div>
                            
                            <div>
                                <x-input-label for="height" :value="__('Hauteur (mm)')" />
                                <x-text-input id="height" name="height" type="number" step="0.1" class="mt-1 block w-full" 
                                    :value="old('height', $template->height)" required />
                            </div>
                            
                            <div>
                                <x-input-label for="size_type" :value="__('Type de taille')" />
                                <select id="size_type" name="size_type" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-white focus:border-accent-yellow focus:ring focus:ring-accent-yellow/30 transition-all">
                                    <option value="fixed" {{ old('size_type', $template->size_type) == 'fixed' ? 'selected' : '' }}>Taille fixe</option>
                                    <option value="continuous" {{ old('size_type', $template->size_type) == 'continuous' ? 'selected' : '' }}>Rouleau continu</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center mt-6">
                                <input id="is_default" name="is_default" type="hidden" value="{{ $template->is_default ? '1' : '0' }}">
                                <input id="is_default_checkbox" type="checkbox" class="rounded bg-gray-800 border-gray-700 text-accent-yellow focus:ring-accent-yellow/30 transition-all" 
                                    {{ old('is_default', $template->is_default) ? 'checked' : '' }} />
                                <label for="is_default_checkbox" class="ml-2 text-gray-300">Définir comme modèle par défaut pour ce type</label>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <x-input-label :value="__('Contenu de l\'étiquette (format texte)')" class="mb-3" />
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-2">
                                    <textarea id="simple_template" name="simple_template" rows="15" 
                                              class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-white focus:border-accent-yellow focus:ring focus:ring-accent-yellow/30 transition-all"
                                    >{{ old('simple_template', $template->simple_template ?? '') }}</textarea>
                                    <p class="mt-2 text-sm text-gray-400">
                                        Entrez le contenu de l'étiquette. Utilisez des variables sous forme @{{ nom_variable }}. Exemple: <br>
                                        <code>Chantier: @{{ client }}<br>Référence: @{{ reference }}<br>Date: @{{ date }}</code>
                                    </p>
                                </div>
                                <div class="md:col-span-1">
                                    <div class="border border-gray-700 rounded-lg p-4 bg-gray-900">
                                        <h4 class="font-semibold text-white mb-3">Variables disponibles</h4>
                                        
                                        <div class="mb-4">
                                            <h5 class="text-sm font-medium text-gray-300 mb-2">Variables communes</h5>
                                            <ul class="text-sm text-gray-400 space-y-1 pl-4">
                                                <li><code>@{{ date }}</code> - Date actuelle</li>
                                                <li><code>@{{ datetime }}</code> - Date et heure</li>
                                                <li><code>@{{ qrcode }}</code> - <strong>Image QR code</strong> (à privilégier)</li>
                                                <li><code>@{{ qrcode_url }}</code> - URL pour le QR code (texte uniquement)</li>
                                                <li><code>@{{ logo }}</code> - <strong>Image du logo</strong> (à privilégier)</li>
                                                <li><code>@{{ logo_url }}</code> - URL du logo (texte uniquement)</li>
                                                <li><code>@{{ societe }}</code> - Nom de votre société</li>
                                            </ul>
                                        </div>
                                        
                                        <div id="chantier-vars" class="mb-4 {{ $template->type == 'chantier' ? '' : 'hidden' }}">
                                            <h5 class="text-sm font-medium text-gray-300 mb-2">Variables Chantier</h5>
                                            <ul class="text-sm text-gray-400 space-y-1 pl-4">
                                                <li><code>@{{ client }}</code> - Nom du client</li>
                                                <li><code>@{{ reference }}</code> - Référence</li>
                                                <li><code>@{{ adresse }}</code> - Adresse</li>
                                                <li><code>@{{ date_debut }}</code> - Date de début</li>
                                                <li><code>@{{ date_fin }}</code> - Date butoir</li>
                                                <li><code>@{{ produits }}</code> - Produits</li>
                                                <li><code>@{{ statut }}</code> - Statut</li>
                                                <li><code>@{{ client_email }}</code> - Email client</li>
                                                <li><code>@{{ client_telephone }}</code> - Téléphone</li>
                                                <li><code>@{{ responsable }}</code> - Responsable</li>
                                                <li><code>@{{ nb_dalles }}</code> - Nombre de dalles</li>
                                                <li><code>@{{ nb_modules }}</code> - Nombre de modules</li>
                                                <li><code>@{{ nb_flightcases }}</code> - Nombre de flightcases</li>
                                                <li><code>@{{ note }}</code> - Notes</li>
                                            </ul>
                                        </div>
                                        
                                        <div id="dalle-vars" class="mb-4 {{ $template->type == 'dalle' ? '' : 'hidden' }}">
                                            <h5 class="text-sm font-medium text-gray-300 mb-2">Variables Dalle</h5>
                                            <ul class="text-sm text-gray-400 space-y-1 pl-4">
                                                <li><code>@{{ chantier }}</code> - Nom du chantier</li>
                                                <li><code>@{{ client }}</code> - Client</li>
                                                <li><code>@{{ numero }}</code> - Numéro</li>
                                                <li><code>@{{ dalle_id }}</code> - ID unique</li>
                                                <li><code>@{{ dimensions }}</code> - Dimensions</li>
                                                <li><code>@{{ produit }}</code> - Type de produit</li>
                                                <li><code>@{{ resolution }}</code> - Résolution</li>
                                                <li><code>@{{ nb_modules }}</code> - Nombre de modules</li>
                                                <li><code>@{{ flightcase }}</code> - Flight case</li>
                                                <li><code>@{{ flightcase_id }}</code> - ID du flightcase</li>
                                                <li><code>@{{ contenu_flightcase }}</code> - Contenu du flightcase</li>
                                                <li><code>@{{ carte_reception }}</code> - Carte réception</li>
                                                <li><code>@{{ date_installation }}</code> - Date installation</li>
                                            </ul>
                                        </div>
                                        
                                        <div id="module-vars" class="mb-4 {{ $template->type == 'module' ? '' : 'hidden' }}">
                                            <h5 class="text-sm font-medium text-gray-300 mb-2">Variables Module</h5>
                                            <ul class="text-sm text-gray-400 space-y-1 pl-4">
                                                <li><code>@{{ module_id }}</code> - ID unique</li>
                                                <li><code>@{{ dalle }}</code> - Dalle</li>
                                                <li><code>@{{ dalle_id }}</code> - ID de la dalle</li>
                                                <li><code>@{{ position }}</code> - Position</li>
                                                <li><code>@{{ colonne }}</code> - Colonne</li>
                                                <li><code>@{{ ligne }}</code> - Ligne</li>
                                                <li><code>@{{ numero_serie }}</code> - Numéro de série</li>
                                                <li><code>@{{ resolution }}</code> - Résolution</li>
                                                <li><code>@{{ led_type }}</code> - Type de LED</li>
                                                <li><code>@{{ dimensions }}</code> - Dimensions</li>
                                                <li><code>@{{ chantier }}</code> - Chantier</li>
                                                <li><code>@{{ client }}</code> - Client</li>
                                                <li><code>@{{ flightcase }}</code> - Flight case</li>
                                                <li><code>@{{ flightcase_id }}</code> - ID du flightcase</li>
                                                <li><code>@{{ statut }}</code> - Statut</li>
                                                <li><code>@{{ produit }}</code> - Produit</li>
                                                <li><code>@{{ reparations }}</code> - Réparations effectuées</li>
                                                <li><code>@{{ date_reparation }}</code> - Date de réparation</li>
                                            </ul>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h5 class="text-sm font-medium text-gray-300 mb-2">Variables Flightcase</h5>
                                            <ul class="text-sm text-gray-400 space-y-1 pl-4">
                                                <li><code>@{{ flightcase_id }}</code> - ID du flightcase</li>
                                                <li><code>@{{ chantier }}</code> - Chantier</li>
                                                <li><code>@{{ client }}</code> - Client</li>
                                                <li><code>@{{ contenu_flightcase }}</code> - Contenu</li>
                                                <li><code>@{{ nb_dalles }}</code> - Nombre de dalles</li>
                                                <li><code>@{{ nb_modules }}</code> - Nombre de modules</li>
                                                <li><code>@{{ numero }}</code> - Numéro du flightcase</li>
                                                <li><code>@{{ date_expedition }}</code> - Date d'expédition</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden field to store the template data as JSON -->
                        <input type="hidden" name="template_data" id="templateData" value="{{ old('template_data', json_encode($template->template_data)) }}">
                        
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.etiquettes.index') }}" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition duration-150 ease-in-out">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-accent-yellow text-white rounded-lg hover:bg-yellow-500 transition duration-150 ease-in-out">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gérer la case à cocher par défaut
            const isDefaultCheckbox = document.getElementById('is_default_checkbox');
            const isDefaultInput = document.getElementById('is_default');
            const typeSelect = document.getElementById('type');
            
            isDefaultCheckbox.addEventListener('change', function() {
                isDefaultInput.value = this.checked ? '1' : '0';
            });
            
            // Afficher les variables correspondant au type d'étiquette sélectionné
            typeSelect.addEventListener('change', function() {
                const chantierVars = document.getElementById('chantier-vars');
                const dalleVars = document.getElementById('dalle-vars');
                const moduleVars = document.getElementById('module-vars');
                
                // Cacher toutes les sections de variables
                chantierVars.classList.add('hidden');
                dalleVars.classList.add('hidden');
                moduleVars.classList.add('hidden');
                
                // Afficher la section correspondant au type sélectionné
                if (this.value === 'chantier') {
                    chantierVars.classList.remove('hidden');
                } else if (this.value === 'dalle') {
                    dalleVars.classList.remove('hidden');
                } else if (this.value === 'module') {
                    moduleVars.classList.remove('hidden');
                }
            });
            
            // Gérer la soumission du formulaire
            document.getElementById('templateForm').addEventListener('submit', function(e) {
                // Mise à jour de l'état par défaut
                isDefaultInput.value = isDefaultCheckbox.checked ? '1' : '0';
                
                // Générer un modèle de données simple basé sur le contenu du textarea
                const simpleTemplate = document.getElementById('simple_template').value;
                
                // Structure de base pour les données du modèle
                const templateData = [];
                
                // Ajouter des éléments de texte simples
                const lines = simpleTemplate.split('\n');
                let yPosition = 10;
                
                // Indique si le contenu texte contient du texte
                let hasContent = false;
                
                lines.forEach((line, index) => {
                    // Ignorer les lignes vides
                    if (line.trim() === '') return;
                    
                    hasContent = true;
                    
                    // Traiter le texte comme un élément simple
                    templateData.push({
                        type: 'text',
                        id: 'element_' + (Date.now() + index),
                        x: 10,
                        y: yPosition,
                        width: 200,
                        height: 20,
                        content: line,
                        fontSize: 14,
                        fontWeight: 'normal',
                        textAlign: 'left',
                        color: '#000000'
                    });
                    
                    // Incrémenter la position Y pour la ligne suivante
                    yPosition += 20;
                });
                
                // Si pas de contenu, mettez au moins un élément vide
                if (!hasContent) {
                    templateData.push({
                        type: 'text',
                        id: 'element_' + Date.now(),
                        x: 10,
                        y: 10,
                        width: 200,
                        height: 20,
                        content: 'Modèle vide',
                        fontSize: 14,
                        fontWeight: 'normal',
                        textAlign: 'left',
                        color: '#000000'
                    });
                }
                
                // Mettre à jour le champ de données du modèle
                document.getElementById('templateData').value = JSON.stringify(templateData);
            });
        });
    </script>
    @endpush
</x-app-layout>