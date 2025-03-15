<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Créer un chantier - Étape 1/5') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Étapes de progression -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="w-full flex items-center">
                        <div class="relative flex flex-col items-center text-accent-blue">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 bg-accent-blue border-accent-blue text-white font-bold flex items-center justify-center">
                                1
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-accent-blue">
                                <span class="font-bold">Client</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-700"></div>
                        <div class="relative flex flex-col items-center text-gray-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-700 text-gray-400 font-bold flex items-center justify-center">
                                2
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-gray-500">
                                <span>Chantier</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-700"></div>
                        <div class="relative flex flex-col items-center text-gray-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-700 text-gray-400 font-bold flex items-center justify-center">
                                3
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-gray-500">
                                <span>Produit</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-700"></div>
                        <div class="relative flex flex-col items-center text-gray-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-700 text-gray-400 font-bold flex items-center justify-center">
                                4
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-gray-500">
                                <span>Interventions</span>
                            </div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-700"></div>
                        <div class="relative flex flex-col items-center text-gray-500">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-700 text-gray-400 font-bold flex items-center justify-center">
                                5
                            </div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium text-gray-500">
                                <span>Rapports</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <form method="POST" action="{{ route('chantiers.store.step1') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Client avec autocomplétion -->
                            <div>
                                <x-client-autocomplete :required="true" label="Client" />
                                <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                                <div class="mt-2">
                                    <button type="button" id="openCreateClientModal" class="text-sm text-blue-400 hover:text-blue-300 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Créer un nouveau client
                                    </button>
                                </div>
                            </div>

                            <!-- Création automatique du nom -->
                            <div class="bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-accent-blue mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-blue-300">Le nom du chantier sera généré automatiquement à partir des informations du client.</span>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" class="text-gray-300" />
                                <textarea id="description" name="description" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="3">{{ old('description') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Date de réception -->
                            <div>
                                <x-input-label for="date_reception" :value="__('Date de réception')" class="text-gray-300" />
                                <x-text-input id="date_reception" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="date" name="date_reception" :value="old('date_reception', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date_reception')" class="mt-2" />
                            </div>

                            <!-- Date butoir -->
                            <div>
                                <x-input-label for="date_butoir" :value="__('Date butoir')" class="text-gray-300" />
                                <x-text-input id="date_butoir" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="date" name="date_butoir" :value="old('date_butoir')" required />
                                <x-input-error :messages="$errors->get('date_butoir')" class="mt-2" />
                            </div>

                            <!-- État -->
                            <div>
                                <x-input-label for="etat" :value="__('État')" class="text-gray-300" />
                                <select id="etat" name="etat" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="non_commence" {{ old('etat') == 'non_commence' ? 'selected' : '' }}>Non commencé</option>
                                    <option value="en_cours" {{ old('etat') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="termine" {{ old('etat') == 'termine' ? 'selected' : '' }}>Terminé</option>
                                </select>
                                <x-input-error :messages="$errors->get('etat')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('chantiers.index') }}" class="btn-action btn-secondary mr-2">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="btn-action btn-primary">
                                {{ __('Suivant') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour créer un client -->
    <div id="createClientModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom glassmorphism rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                        Créer un nouveau client
                    </h3>
                    <form id="createClientForm" class="mt-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-300">Nom</label>
                                <input type="text" name="nom" id="nom" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                            </div>
                            <div>
                                <label for="prenom" class="block text-sm font-medium text-gray-300">Prénom</label>
                                <input type="text" name="prenom" id="prenom" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                            </div>
                            <div>
                                <label for="societe" class="block text-sm font-medium text-gray-300">Société</label>
                                <input type="text" name="societe" id="societe" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-300">Téléphone</label>
                                <input type="text" name="telephone" id="telephone" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                                <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="adresse" class="block text-sm font-medium text-gray-300">Adresse</label>
                                <input type="text" name="adresse" id="adresse" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                            </div>
                            <div>
                                <label for="code_postal" class="block text-sm font-medium text-gray-300">Code Postal</label>
                                <input type="text" name="code_postal" id="code_postal" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                            </div>
                            <div>
                                <label for="ville" class="block text-sm font-medium text-gray-300">Ville</label>
                                <input type="text" name="ville" id="ville" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="pays" class="block text-sm font-medium text-gray-300">Pays</label>
                                <input type="text" name="pays" id="pays" value="France" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-300">Notes</label>
                                <textarea name="notes" id="notes" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="2"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="submitCreateClient" class="btn-action btn-primary">
                        Créer le client
                    </button>
                    <button type="button" id="closeClientModal" class="btn-action btn-secondary mr-2">
                        Annuler
                    </button>
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
            let bgColor, borderColor, textColor, icon;
            if (type === 'success') {
                bgColor = 'bg-green-500/80';
                borderColor = 'border-green-400';
                textColor = 'text-white';
                icon = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'error') {
                bgColor = 'bg-red-500/80';
                borderColor = 'border-red-400';
                textColor = 'text-white';
                icon = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            } else {
                bgColor = 'bg-blue-500/80';
                borderColor = 'border-blue-400';
                textColor = 'text-white';
                icon = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            
            // Construire le HTML du toast
            toast.className = `${bgColor} ${borderColor} ${textColor} px-4 py-3 rounded-lg shadow-lg mb-3 flex items-center transform transition-all duration-300 ease-in-out translate-x-full opacity-0`;
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