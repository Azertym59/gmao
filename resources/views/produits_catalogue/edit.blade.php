<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Modifier un produit du catalogue') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <form method="POST" action="{{ route('produits-catalogue.update', $produit) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Marque -->
                            <div>
                                <x-input-label for="marque" :value="__('Marque')" class="text-gray-300" />
                                <x-text-input id="marque" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="text" name="marque" :value="old('marque', $produit->marque)" required autofocus />
                                <x-input-error :messages="$errors->get('marque')" class="mt-2" />
                            </div>

                            <!-- Modèle -->
                            <div>
                                <x-input-label for="modele" :value="__('Modèle')" class="text-gray-300" />
                                <x-text-input id="modele" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="text" name="modele" :value="old('modele', $produit->modele)" required />
                                <x-input-error :messages="$errors->get('modele')" class="mt-2" />
                            </div>

                            <!-- Pitch -->
                            <div>
                                <x-input-label for="pitch" :value="__('Pitch (mm)')" class="text-gray-300" />
                                <x-text-input id="pitch" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="number" name="pitch" :value="old('pitch', $produit->pitch)" step="0.01" min="0.1" required />
                                <x-input-error :messages="$errors->get('pitch')" class="mt-2" />
                            </div>

                            <!-- Utilisation -->
                            <div>
                                <x-input-label for="utilisation" :value="__('Utilisation')" class="text-gray-300" />
                                <select id="utilisation" name="utilisation" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" required>
                                    <option value="indoor" {{ old('utilisation', $produit->utilisation) == 'indoor' ? 'selected' : '' }}>Indoor</option>
                                    <option value="outdoor" {{ old('utilisation', $produit->utilisation) == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                                </select>
                                <x-input-error :messages="$errors->get('utilisation')" class="mt-2" />
                            </div>

                            <!-- Électronique -->
                            <div>
                                <x-input-label for="electronique" :value="__('Électronique')" class="text-gray-300" />
                                <select id="electronique" name="electronique" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" required onchange="toggleElectroniqueDetail()">
                                    <option value="nova" {{ old('electronique', $produit->electronique) == 'nova' ? 'selected' : '' }}>Nova</option>
                                    <option value="linsn" {{ old('electronique', $produit->electronique) == 'linsn' ? 'selected' : '' }}>Linsn</option>
                                    <option value="dbstar" {{ old('electronique', $produit->electronique) == 'dbstar' ? 'selected' : '' }}>DBStar</option>
                                    <option value="brompton" {{ old('electronique', $produit->electronique) == 'brompton' ? 'selected' : '' }}>Brompton</option>
                                    <option value="autre" {{ old('electronique', $produit->electronique) == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                <x-input-error :messages="$errors->get('electronique')" class="mt-2" />
                            </div>

                            <!-- Électronique Détail (conditionnel) -->
                            <div id="electronique_detail_container" class="{{ old('electronique', $produit->electronique) == 'autre' ? '' : 'hidden' }}">
                                <x-input-label for="electronique_detail" :value="__('Précisez l\'électronique')" class="text-gray-300" />
                                <x-text-input id="electronique_detail" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="text" name="electronique_detail" :value="old('electronique_detail', $produit->electronique_detail)" />
                                <x-input-error :messages="$errors->get('electronique_detail')" class="mt-2" />
                            </div>

                            <!-- URL de l'image -->
                            <div>
                                <x-input-label for="image_url" :value="__('URL de l\'image (optionnel)')" class="text-gray-300" />
                                <x-text-input id="image_url" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" type="url" name="image_url" :value="old('image_url', $produit->image_url)" />
                                <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" class="text-gray-300" />
                                <textarea id="description" name="description" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500/50" rows="3">{{ old('description', $produit->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('produits-catalogue.index') }}" class="btn-action btn-secondary flex items-center mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('Annuler') }}
                            </a>
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ __('Mettre à jour') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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