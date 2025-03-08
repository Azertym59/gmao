<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Ajouter un client') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <form method="POST" action="{{ route('clients.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <x-input-label for="nom" :value="__('Nom')" class="text-gray-300" />
                                <x-text-input id="nom" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="nom" :value="old('nom')" required autofocus />
                                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                            </div>

                            <!-- Prénom -->
                            <div>
                                <x-input-label for="prenom" :value="__('Prénom')" class="text-gray-300" />
                                <x-text-input id="prenom" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="prenom" :value="old('prenom')" required />
                                <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                            </div>

                            <!-- Société -->
                            <div>
                                <x-input-label for="societe" :value="__('Société')" class="text-gray-300" />
                                <x-text-input id="societe" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="societe" :value="old('societe')" />
                                <x-input-error :messages="$errors->get('societe')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" class="text-gray-300" />
                                <x-text-input id="email" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="email" name="email" :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <x-input-label for="telephone" :value="__('Téléphone')" class="text-gray-300" />
                                <x-text-input id="telephone" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="telephone" :value="old('telephone')" required />
                                <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                            </div>

                            <!-- Adresse -->
                            <div class="md:col-span-2">
                                <x-input-label for="adresse" :value="__('Adresse')" class="text-gray-300" />
                                <x-text-input id="adresse" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="adresse" :value="old('adresse')" required />
                                <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
                            </div>

                            <!-- Code postal -->
                            <div>
                                <x-input-label for="code_postal" :value="__('Code postal')" class="text-gray-300" />
                                <x-text-input id="code_postal" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="code_postal" :value="old('code_postal')" required />
                                <x-input-error :messages="$errors->get('code_postal')" class="mt-2" />
                            </div>

                            <!-- Ville -->
                            <div>
                                <x-input-label for="ville" :value="__('Ville')" class="text-gray-300" />
                                <x-text-input id="ville" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="ville" :value="old('ville')" required />
                                <x-input-error :messages="$errors->get('ville')" class="mt-2" />
                            </div>

                            <!-- Pays -->
                            <div>
                                <x-input-label for="pays" :value="__('Pays')" class="text-gray-300" />
                                <x-text-input id="pays" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="pays" :value="old('pays', 'France')" required />
                                <x-input-error :messages="$errors->get('pays')" class="mt-2" />
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Notes')" class="text-gray-300" />
                                <textarea id="notes" name="notes" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="3">{{ old('notes') }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('clients.index') }}" class="btn-action btn-secondary mr-2">
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
</x-app-layout>