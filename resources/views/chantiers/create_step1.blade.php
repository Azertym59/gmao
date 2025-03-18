<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            <!-- Debug: Type de projet = {{ $type ?? 'non défini' }} -->
            @if (isset($type) && $type === 'vente')
                {{ __('Créer un projet de vente - Étape 1/5') }}
            @else
                {{ __('Créer un projet de maintenance - Étape 1/5') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto">
            <!-- Étapes de progression -->
            <div class="mb-10">
                <div class="flex items-center justify-between">
                    <div class="w-full flex items-center">
                        <div class="relative flex flex-col items-center">
                            <div class="step-button active">
                                <span class="relative z-10 font-bold">1</span>
                            </div>
                            <div class="text-center mt-1 w-32">
                                <span class="step-text text-accent-primary font-semibold">Client</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-600/30"></div>
                        <div class="relative flex flex-col items-center">
                            <div class="step-button inactive">
                                <span class="relative z-10">2</span>
                            </div>
                            <div class="text-center mt-1 w-32">
                                <span class="step-text text-gray-400">Chantier</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-600/30"></div>
                        <div class="relative flex flex-col items-center">
                            <div class="step-button inactive">
                                <span class="relative z-10">3</span>
                            </div>
                            <div class="text-center mt-1 w-32">
                                <span class="step-text text-gray-400">Produit</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-600/30"></div>
                        <div class="relative flex flex-col items-center">
                            <div class="step-button inactive">
                                <span class="relative z-10">4</span>
                            </div>
                            <div class="text-center mt-1 w-32">
                                <span class="step-text text-gray-400">Interventions</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-600/30"></div>
                        <div class="relative flex flex-col items-center">
                            <div class="step-button inactive">
                                <span class="relative z-10">5</span>
                            </div>
                            <div class="text-center mt-1 w-32">
                                <span class="step-text text-gray-400">Rapports</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-2xl border border-white/5">
                <div class="p-8 border-b border-gray-700/50">
                    <h3 class="text-xl font-medium text-white mb-6 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Informations client
                    </h3>
                    
                    <form method="POST" action="{{ route('chantiers.store.step1') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Client avec autocomplétion -->
                            <div>
                                <x-client-autocomplete :required="true" label="Client" />
                                <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                                <div class="mt-3">
                                    <button type="button" id="openCreateClientModal" class="text-sm text-accent-tertiary hover:text-accent-blue transition-colors flex items-center font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Créer un nouveau client
                                    </button>
                                </div>
                            </div>

                            <!-- Création automatique du nom -->
                            <div class="bg-accent-blue/10 border border-accent-blue/20 p-4 rounded-xl flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-tertiary mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-gray-200">Le nom du chantier sera généré automatiquement à partir des informations du client.</span>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" class="text-gray-200 font-medium" />
                                <textarea id="description" name="description" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30 resize-none" rows="3" placeholder="Description détaillée du projet...">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Date de réception -->
                            <div>
                                <x-input-label for="date_reception" :value="__('Date de réception')" class="text-gray-200 font-medium" />
                                <x-text-input id="date_reception" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" type="date" name="date_reception" :value="old('date_reception', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date_reception')" class="mt-2" />
                            </div>

                            <!-- Date butoir -->
                            <div>
                                <x-input-label for="date_butoir" :value="__('Date butoir')" class="text-gray-200 font-medium" />
                                <x-text-input id="date_butoir" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" type="date" name="date_butoir" :value="old('date_butoir')" />
                                <p class="text-xs text-accent-tertiary mt-2">Obligatoire pour SAV / Réparation, optionnel pour Vente / Achat client</p>
                                <x-input-error :messages="$errors->get('date_butoir')" class="mt-2" />
                            </div>

                            <!-- État -->
                            <div>
                                <x-input-label for="etat" :value="__('État')" class="text-gray-200 font-medium" />
                                <select id="etat" name="etat" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30">
                                    <option value="non_commence" {{ old('etat') == 'non_commence' ? 'selected' : '' }}>Non commencé</option>
                                    <option value="en_cours" {{ old('etat') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="termine" {{ old('etat') == 'termine' ? 'selected' : '' }}>Terminé</option>
                                </select>
                                <x-input-error :messages="$errors->get('etat')" class="mt-2" />
                            </div>
                            
                            <!-- Séparateur -->
                            <div class="md:col-span-2 border-t border-gray-700/30 my-6"></div>
                            
                            <!-- Type de projet (prédéfini selon la sélection) -->
                            <div>
                                <x-input-label for="type_projet" :value="__('Type de projet')" class="text-gray-200 font-medium" />
                                <select id="type_projet" name="is_client_achat" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30">
                                    @if (isset($type) && $type === 'vente')
                                        <option value="1" selected>Vente / Achat client</option>
                                    @else
                                        <option value="0" selected>SAV / Réparation</option>
                                    @endif
                                </select>
                                <p class="text-xs text-accent-tertiary mt-2">Le type de projet est prédéfini selon votre choix précédent</p>
                                <x-input-error :messages="$errors->get('is_client_achat')" class="mt-2" />
                            </div>
                            
                            <!-- Garantie -->
                            <div>
                                <div class="flex items-center mt-7 p-2 rounded-lg hover:bg-gray-700/20 transition-colors">
                                    <input type="checkbox" id="is_under_warranty" name="is_under_warranty" value="1" {{ old('is_under_warranty') ? 'checked' : '' }} class="rounded bg-gray-700/50 border-gray-600/50 text-accent-primary focus:ring-accent-primary/30 h-5 w-5">
                                    <label for="is_under_warranty" class="ml-3 text-gray-200 font-medium">Produit sous garantie</label>
                                </div>
                            </div>
                            
                            <!-- Options de garantie (affichées seulement si sous garantie) -->
                            <div id="warranty_options" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 mt-2 bg-accent-blue/5 p-6 rounded-xl border border-accent-blue/20" style="display: none;">
                                <div>
                                    <x-input-label for="warranty_end_date" :value="__('Date de fin de garantie')" class="text-gray-200 font-medium" />
                                    <x-text-input id="warranty_end_date" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" type="date" name="warranty_end_date" :value="old('warranty_end_date')" />
                                </div>
                                
                                <div>
                                    <x-input-label for="warranty_type" :value="__('Type de garantie')" class="text-gray-200 font-medium" />
                                    <select id="warranty_type" name="warranty_type" class="block mt-2 w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30">
                                        <option value="">-- Sélectionnez --</option>
                                        <option value="standard" {{ old('warranty_type') == 'standard' ? 'selected' : '' }}>Standard (1 an)</option>
                                        <option value="extended" {{ old('warranty_type') == 'extended' ? 'selected' : '' }}>Étendue (2 ans)</option>
                                        <option value="premium" {{ old('warranty_type') == 'premium' ? 'selected' : '' }}>Premium (3 ans)</option>
                                        <option value="custom" {{ old('warranty_type') == 'custom' ? 'selected' : '' }}>Personnalisée</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-action-button tag="a" href="{{ route('chantiers.index') }}" color="dark" class="mr-3">
                                {{ __('Annuler') }}
                            </x-action-button>
                            <x-action-button tag="button" type="submit" color="primary" class="flex items-center">
                                {{ __('Suivant') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </x-action-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour créer un client -->
    <div id="createClientModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom glassmorphism rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/10">
                <div class="px-6 pt-6 pb-4 sm:p-8">
                    <h3 class="text-xl font-medium text-white mb-5 flex items-center" id="modal-title">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Créer un nouveau client
                    </h3>
                    <form id="createClientForm" class="mt-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-200">Nom</label>
                                <input type="text" name="nom" id="nom" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-200">Prénom</label>
                                <input type="text" name="prenom" id="prenom" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div>
                                <label for="societe" class="block text-sm font-medium text-gray-200">Société</label>
                                <input type="text" name="societe" id="societe" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30">
                            </div>
                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-200">Téléphone</label>
                                <input type="text" name="telephone" id="telephone" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-200">Email</label>
                                <input type="email" name="email" id="email" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="adresse" class="block text-sm font-medium text-gray-200">Adresse</label>
                                <input type="text" name="adresse" id="adresse" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div>
                                <label for="code_postal" class="block text-sm font-medium text-gray-200">Code Postal</label>
                                <input type="text" name="code_postal" id="code_postal" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div>
                                <label for="ville" class="block text-sm font-medium text-gray-200">Ville</label>
                                <input type="text" name="ville" id="ville" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="pays" class="block text-sm font-medium text-gray-200">Pays</label>
                                <input type="text" name="pays" id="pays" value="France" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-200">Notes</label>
                                <textarea name="notes" id="notes" class="mt-2 block w-full rounded-xl bg-gray-700/50 border-gray-600/50 text-white focus:border-accent-primary focus:ring focus:ring-accent-primary/30 resize-none" rows="2"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="px-6 py-4 sm:px-8 sm:py-5 border-t border-gray-700/30 sm:flex sm:flex-row-reverse">
                    <x-action-button tag="button" id="submitCreateClient" color="primary" class="w-full sm:w-auto flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Créer le client
                    </x-action-button>
                    <x-action-button tag="button" id="closeClientModal" color="dark" class="mt-3 sm:mt-0 sm:mr-3 w-full sm:w-auto flex items-center justify-center">
                        Annuler
                    </x-action-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteneur pour les notifications toast -->
    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

    <!-- Scripts pour la modal et les notifications -->
    <script>
        // Fonction pour afficher une notification toast
        function showToast(message, type = 'success', duration = 3000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            // Déterminer la classe de couleur en fonction du type
            let bgColor, textColor, icon;
            if (type === 'success') {
                bgColor = 'bg-accent-primary/90';
                textColor = 'text-white';
                icon = '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'error') {
                bgColor = 'bg-error/90';
                textColor = 'text-white';
                icon = '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else {
                bgColor = 'bg-accent-blue/90';
                textColor = 'text-white';
                icon = '<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            
            // Construire le HTML du toast
            toast.className = `${bgColor} ${textColor} px-4 py-3 rounded-xl shadow-lg mb-3 flex items-center transform transition-all duration-300 ease-in-out translate-x-full opacity-0 backdrop-blur-sm`;
            toast.innerHTML = `
                ${icon}
                <div>${message}</div>
            `;
            
            // Ajouter au container
            container.appendChild(toast);
            
            // Animation d'entrée
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // Animation de sortie et suppression
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    container.removeChild(toast);
                }, 300);
            }, duration);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des options de garantie
            const isUnderWarrantyCheckbox = document.getElementById('is_under_warranty');
            const warrantyOptionsContainer = document.getElementById('warranty_options');
            const typeProjetSelect = document.getElementById('type_projet');
            
            // Initialisation spécifique au type de projet
            @if(isset($type) && $type === 'vente')
                // Pour un projet de vente, pré-cocher la garantie
                if (!isUnderWarrantyCheckbox.checked) {
                    isUnderWarrantyCheckbox.checked = true;
                }
                
                // Suggérer une date de fin de garantie par défaut (+1 an)
                const warrantyEndDateInput = document.getElementById('warranty_end_date');
                if (!warrantyEndDateInput.value) {
                    const today = new Date();
                    today.setFullYear(today.getFullYear() + 1);
                    warrantyEndDateInput.value = today.toISOString().split('T')[0];
                }
                
                // Afficher les options de garantie
                warrantyOptionsContainer.style.display = 'grid';
            @endif
            
            // Fonction pour gérer l'affichage des options de garantie
            function toggleWarrantyOptions() {
                if (isUnderWarrantyCheckbox.checked) {
                    warrantyOptionsContainer.style.display = 'grid';
                    
                    // Si c'est une vente client, suggérer une date de fin de garantie par défaut
                    if (typeProjetSelect.value === '1') {
                        const warrantyEndDateInput = document.getElementById('warranty_end_date');
                        if (!warrantyEndDateInput.value) {
                            const today = new Date();
                            // Par défaut, garantie d'un an pour les nouveaux achats
                            today.setFullYear(today.getFullYear() + 1);
                            warrantyEndDateInput.value = today.toISOString().split('T')[0];
                        }
                    }
                } else {
                    warrantyOptionsContainer.style.display = 'none';
                }
            }
            
            // Initialiser l'état à partir de l'état actuel du checkbox
            toggleWarrantyOptions();
            
            // Ajouter les écouteurs d'événements
            isUnderWarrantyCheckbox.addEventListener('change', toggleWarrantyOptions);
            
            // Gérer le champ date_butoir
            const dateButoir = document.getElementById('date_butoir');
            
            // Fonction pour gérer l'obligation du champ date_butoir en fonction du type de projet
            function toggleDateButoir() {
                if (typeProjetSelect.value === '0') { // SAV / Réparation
                    dateButoir.setAttribute('required', 'required');
                } else { // Vente / Achat client
                    dateButoir.removeAttribute('required');
                }
            }
            
            // Initialiser l'état du champ date_butoir
            toggleDateButoir();
            
            // Définir un comportement conditionnel pour le type de projet
            typeProjetSelect.addEventListener('change', function() {
                // Pour un achat client, présélectionner automatiquement la garantie
                if (this.value === '1' && !isUnderWarrantyCheckbox.checked) {
                    isUnderWarrantyCheckbox.checked = true;
                    toggleWarrantyOptions();
                }
                
                // Mettre à jour l'obligation du champ date_butoir
                toggleDateButoir();
            });
            
            // Gestion du modal client
            const clientModal = document.getElementById('createClientModal');
            const openModalBtn = document.getElementById('openCreateClientModal');
            const closeModalBtn = document.getElementById('closeClientModal');
            const submitBtn = document.getElementById('submitCreateClient');
            const clientForm = document.getElementById('createClientForm');
            const clientSelect = document.getElementById('client_id');

            // Ouvrir la modal
            openModalBtn.addEventListener('click', function(e) {
                e.preventDefault();
                clientModal.classList.remove('hidden');
            });

            // Fermer la modal
            closeModalBtn.addEventListener('click', function() {
                clientModal.classList.add('hidden');
            });

            // Soumettre le formulaire
            submitBtn.addEventListener('click', function() {
                const formData = new FormData(clientForm);
                
                fetch('{{ route("chantiers.store.client-ajax") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Ajouter le nouveau client au sélecteur
                        const newOption = document.createElement('option');
                        newOption.value = data.client.id;
                        newOption.textContent = data.client.nom_complet + ' (' + (data.client.societe || 'Sans société') + ')';
                        newOption.selected = true;
                        clientSelect.appendChild(newOption);
                        
                        // Fermer la modal
                        clientModal.classList.add('hidden');
                        
                        // Réinitialiser le formulaire
                        clientForm.reset();
                        
                        // Notification élégante
                        showToast(data.message, 'success');
                    } else {
                        showToast('Erreur: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showToast('Une erreur est survenue lors de la création du client', 'error');
                });
            });
        });
    </script>
</x-app-layout>