<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Création de chantier - Étape 2/4 : Chantier') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Indicateur de progression -->
            <div class="mb-6">
                <div class="flex justify-between mb-2">
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-500 text-white font-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="ml-2 font-medium text-green-500">Client</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-green-500 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-accent-blue text-white font-bold">2</div>
                        <span class="ml-2 font-medium text-white">Chantier</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-600 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-400 font-bold">3</div>
                        <span class="ml-2 font-medium text-gray-400">Produit</span>
                    </div>
                    <div class="flex-1 mx-4 border-t-2 border-gray-600 self-center"></div>
                    <div class="flex items-center">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-700 text-gray-400 font-bold">4</div>
                        <span class="ml-2 font-medium text-gray-400">Rapports</span>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="mb-6 bg-blue-900/30 border border-blue-500/30 p-4 rounded-xl">
                        <h3 class="font-medium text-blue-300 mb-2">Client sélectionné</h3>
                        <p class="text-gray-300"><span class="font-medium">Nom:</span> {{ $client->nom_complet }}</p>
                        @if($client->societe)
                            <p class="text-gray-300"><span class="font-medium">Société:</span> {{ $client->societe }}</p>
                        @endif
                        @if($client->email)
                            <p class="text-gray-300"><span class="font-medium">Email:</span> {{ $client->email }}</p>
                        @endif
                    </div>

                    <h3 class="text-lg font-semibold text-white mb-4">Informations du chantier</h3>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-500/30 border border-red-500/50 text-red-400 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('chantiers.store.step2', $chantier) }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <x-input-label for="nom" :value="__('Nom du chantier')" class="text-gray-300" />
                                <x-text-input id="nom" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="nom" :value="old('nom', $chantierData['nom'] ?? '')" required />
                                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                            </div>

                            <!-- Référence -->
                            <div>
                                <x-input-label for="reference" :value="__('Référence')" class="text-gray-300" />
                                <x-text-input id="reference" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="reference" :value="old('reference', $chantierData['reference'] ?? '')" />
                                <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                            </div>

                            <!-- Date de réception -->
                            <div>
                                <x-input-label for="date_reception" :value="__('Date de réception')" class="text-gray-300" />
                                <x-text-input id="date_reception" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="date" name="date_reception" :value="old('date_reception', $chantierData['date_reception'] ?? date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date_reception')" class="mt-2" />
                            </div>

                            <!-- Date butoir -->
                            <div>
                                <x-input-label for="date_butoir" :value="__('Date butoir')" class="text-gray-300" />
                                <x-text-input id="date_butoir" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="date" name="date_butoir" :value="old('date_butoir', $chantierData['date_butoir'] ?? date('Y-m-d', strtotime('+1 week')))" required />
                                <x-input-error :messages="$errors->get('date_butoir')" class="mt-2" />
                            </div>

                            <!-- État -->
                            <div>
                                <x-input-label for="etat" :value="__('État')" class="text-gray-300" />
                                <select id="etat" name="etat" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="non_commence" {{ old('etat', $chantierData['etat'] ?? 'non_commence') == 'non_commence' ? 'selected' : '' }}>Non commencé</option>
                                    <option value="en_cours" {{ old('etat', $chantierData['etat'] ?? '') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="termine" {{ old('etat', $chantierData['etat'] ?? '') == 'termine' ? 'selected' : '' }}>Terminé</option>
                                </select>
                                <x-input-error :messages="$errors->get('etat')" class="mt-2" />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" class="text-gray-300" />
                                <textarea id="description" name="description" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="4">{{ old('description', $chantierData['description'] ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <a href="{{ route('chantiers.create.step1') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Retour
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Suivant
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
</x-app-layout>