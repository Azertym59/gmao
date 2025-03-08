<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Nouveau chantier - Étape 2: Sélection du produit') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-white">Étape 2 sur 3: Choisissez un produit</h3>
                        <p class="text-gray-400 mt-1">Sélectionnez un produit depuis le catalogue ou créez un nouveau produit.</p>
                    </div>

                    <!-- Étapes de progression -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div class="w-full bg-gray-700 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 66%"></div>
                            </div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-gray-400">
                            <span>Infos client</span>
                            <span class="text-indigo-400 font-medium">Produit</span>
                            <span>Configuration</span>
                        </div>
                    </div>

                    <!-- Onglets -->
                    <div class="mb-6">
                        <div class="flex border-b border-gray-700">
                            <button id="tab-catalogue" class="px-4 py-2 font-medium text-white border-b-2 border-indigo-500" onclick="showTab('catalogue')">
                                Catalogue de produits
                            </button>
                            <button id="tab-nouveau" class="px-4 py-2 font-medium text-gray-400" onclick="showTab('nouveau')">
                                Nouveau produit
                            </button>
                        </div>
                    </div>

                    <!-- Section catalogue de produits -->
                    <div id="catalogue-section">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse ($produitsCatalogue as $produit)
                                <div class="glassmorphism p-4 rounded-lg border border-gray-700 hover:bg-gray-700/30 transition duration-200">
                                    <form method="POST" action="{{ route('chantiers.store.step2') }}">
                                        @csrf
                                        <input type="hidden" name="from_catalogue" value="1">
                                        <input type="hidden" name="catalogue_id" value="{{ $produit->id }}">
                                        <input type="hidden" name="marque" value="{{ $produit->marque }}">
                                        <input type="hidden" name="modele" value="{{ $produit->modele }}">
                                        <input type="hidden" name="pitch" value="{{ $produit->pitch }}">
                                        <input type="hidden" name="utilisation" value="{{ $produit->utilisation }}">
                                        <input type="hidden" name="electronique" value="{{ $produit->electronique }}">
                                        <input type="hidden" name="electronique_detail" value="{{ $produit->electronique_detail }}">
                                        
                                        <div class="flex justify-between items-start mb-2">
                                            <h4 class="font-medium text-white">{{ $produit->marque }} {{ $produit->modele }}</h4>
                                            <span class="badge {{ $produit->utilisation == 'indoor' ? 'badge-info' : 'badge-warning' }}">
                                                {{ $produit->utilisation == 'indoor' ? 'Indoor' : 'Outdoor' }}
                                            </span>
                                        </div>

                                        <div class="text-sm mb-4 text-gray-300">
                                            <p><span class="font-medium">Pitch:</span> {{ $produit->pitch }} mm</p>
                                            <p><span class="font-medium">Électronique:</span> 
                                                @if($produit->electronique == 'autre')
                                                    {{ $produit->electronique_detail }}
                                                @else
                                                    {{ ucfirst($produit->electronique) }}
                                                @endif
                                            </p>
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="submit" class="btn-action btn-primary">
                                                Sélectionner ce produit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @empty
                                <div class="col-span-3 text-center py-8 glassmorphism rounded-lg border border-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <p class="text-gray-400 mb-4">Aucun produit trouvé dans le catalogue.</p>
                                    <button onclick="showTab('nouveau')" class="inline-block px-4 py-2 bg-accent-yellow text-white rounded-lg hover:bg-yellow-500 transition duration-150 ease-in-out">
                                        Créer un nouveau produit
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Section nouveau produit -->
                    <div id="nouveau-section" class="hidden">
                        <form method="POST" action="{{ route('chantiers.store.step2') }}">
                            @csrf
                            <input type="hidden" name="from_catalogue" value="0">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Marque -->
                                <div>
                                    <x-input-label for="marque" :value="__('Marque')" class="text-gray-300" />
                                    <x-text-input id="marque" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="text" name="marque" :value="old('marque')" required autofocus />
                                    <x-input-error :messages="$errors->get('marque')" class="mt-2" />
                                </div>

                                <!-- Modèle -->
                                <div>
                                    <x-input-label for="modele" :value="__('Modèle')" class="text-gray-300" />
                                    <x-text-input id="modele" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="text" name="modele" :value="old('modele')" required />
                                    <x-input-error :messages="$errors->get('modele')" class="mt-2" />
                                </div>

                                <!-- Pitch -->
                                <div>
                                    <x-input-label for="pitch" :value="__('Pitch (mm)')" class="text-gray-300" />
                                    <x-text-input id="pitch" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="pitch" :value="old('pitch')" step="0.01" min="0.1" required />
                                    <x-input-error :messages="$errors->get('pitch')" class="mt-2" />
                                </div>

                                <!-- Utilisation -->
                                <div>
                                    <x-input-label for="utilisation" :value="__('Utilisation')" class="text-gray-300" />
                                    <select id="utilisation" name="utilisation" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" required>
                                        <option value="indoor" {{ old('utilisation') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                                        <option value="outdoor" {{ old('utilisation') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('utilisation')" class="mt-2" />
                                </div>

                                <!-- Électronique -->
                                <div>
                                    <x-input-label for="electronique" :value="__('Électronique')" class="text-gray-300" />
                                    <select id="electronique" name="electronique" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" required onchange="toggleElectroniqueDetail()">
                                        <option value="nova" {{ old('electronique') == 'nova' ? 'selected' : '' }}>Nova</option>
                                        <option value="linsn" {{ old('electronique') == 'linsn' ? 'selected' : '' }}>Linsn</option>
                                        <option value="dbstar" {{ old('electronique') == 'dbstar' ? 'selected' : '' }}>DBStar</option>
                                        <option value="brompton" {{ old('electronique') == 'brompton' ? 'selected' : '' }}>Brompton</option>
                                        <option value="autre" {{ old('electronique') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('electronique')" class="mt-2" />
                                </div>

                                <!-- Électronique Détail (conditionnel) -->
                                <div id="electronique_detail_container" class="{{ old('electronique') == 'autre' ? '' : 'hidden' }}">
                                    <x-input-label for="electronique_detail" :value="__('Précisez l\'électronique')" class="text-gray-300" />
                                    <x-text-input id="electronique_detail" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="text" name="electronique_detail" :value="old('electronique_detail')" />
                                    <x-input-error :messages="$errors->get('electronique_detail')" class="mt-2" />
                                </div>

                                <!-- Option pour ajouter au catalogue -->
                                <div class="md:col-span-2">
                                    <div class="flex items-center">
                                        <input id="add_to_catalogue" name="add_to_catalogue" type="checkbox" class="h-4 w-4 rounded border-gray-600 bg-gray-700 text-indigo-600 focus:ring-indigo-500">
                                        <label for="add_to_catalogue" class="ml-2 block text-sm text-gray-300">
                                            Ajouter ce produit au catalogue pour une utilisation future
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-6">
                                <a href="{{ route('chantiers.create.step1') }}" class="btn-action btn-secondary flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    {{ __('Retour') }}
                                </a>
                                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    {{ __('Continuer') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tab) {
            if (tab === 'catalogue') {
                document.getElementById('catalogue-section').classList.remove('hidden');
                document.getElementById('nouveau-section').classList.add('hidden');
                document.getElementById('tab-catalogue').classList.add('text-white', 'border-b-2', 'border-indigo-500');
                document.getElementById('tab-catalogue').classList.remove('text-gray-400');
                document.getElementById('tab-nouveau').classList.remove('text-white', 'border-b-2', 'border-indigo-500');
                document.getElementById('tab-nouveau').classList.add('text-gray-400');
            } else {
                document.getElementById('catalogue-section').classList.add('hidden');
                document.getElementById('nouveau-section').classList.remove('hidden');
                document.getElementById('tab-nouveau').classList.add('text-white', 'border-b-2', 'border-indigo-500');
                document.getElementById('tab-nouveau').classList.remove('text-gray-400');
                document.getElementById('tab-catalogue').classList.remove('text-white', 'border-b-2', 'border-indigo-500');
                document.getElementById('tab-catalogue').classList.add('text-gray-400');
            }
        }

        function toggleElectroniqueDetail() {
            const electronique = document.getElementById('electronique');
            const electronique_detail_container = document.getElementById('electronique_detail_container');
            
            if (electronique.value === 'autre') {
                electronique_detail_container.classList.remove('hidden');
            } else {
                electronique_detail_container.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>