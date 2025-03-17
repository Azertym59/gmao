<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Modifier le client') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <form method="POST" action="{{ route('clients.update', $client) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Civilité -->
                            <div>
                                <x-input-label for="civilite" :value="__('Civilité')" class="text-gray-300" />
                                <select id="civilite" name="civilite" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="M." {{ old('civilite', $client->civilite) == 'M.' ? 'selected' : '' }}>M.</option>
                                    <option value="Mme" {{ old('civilite', $client->civilite) == 'Mme' ? 'selected' : '' }}>Mme</option>
                                </select>
                                <x-input-error :messages="$errors->get('civilite')" class="mt-2" />
                            </div>
                            
                            <!-- Nom -->
                            <div>
                                <x-input-label for="nom" :value="__('Nom')" class="text-gray-300" />
                                <x-text-input id="nom" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="nom" :value="old('nom', $client->nom)" required autofocus />
                                <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                            </div>

                            <!-- Prénom -->
                            <div>
                                <x-input-label for="prenom" :value="__('Prénom')" class="text-gray-300" />
                                <x-text-input id="prenom" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="prenom" :value="old('prenom', $client->prenom)" required />
                                <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                            </div>

                            <!-- Société -->
                            <div>
                                <x-input-label for="societe" :value="__('Société')" class="text-gray-300" />
                                <x-text-input id="societe" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="societe" :value="old('societe', $client->societe)" />
                                <x-input-error :messages="$errors->get('societe')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" class="text-gray-300" />
                                <x-text-input id="email" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="email" name="email" :value="old('email', $client->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Téléphone -->
                            <div>
                                <x-input-label for="telephone" :value="__('Téléphone')" class="text-gray-300" />
                                <x-text-input id="telephone" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="telephone" :value="old('telephone', $client->telephone)" required />
                                <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                            </div>

                            <!-- Adresse -->
                            <div class="md:col-span-2">
                                <x-input-label for="adresse" :value="__('Adresse')" class="text-gray-300" />
                                <x-text-input id="adresse" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="adresse" :value="old('adresse', $client->adresse)" required />
                                <x-input-error :messages="$errors->get('adresse')" class="mt-2" />
                            </div>

                            <!-- Code postal -->
                            <div>
                                <x-input-label for="code_postal" :value="__('Code postal')" class="text-gray-300" />
                                <x-text-input id="code_postal" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="code_postal" :value="old('code_postal', $client->code_postal)" required />
                                <x-input-error :messages="$errors->get('code_postal')" class="mt-2" />
                            </div>

                            <!-- Ville -->
                            <div>
                                <x-input-label for="ville" :value="__('Ville')" class="text-gray-300" />
                                <x-text-input id="ville" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="ville" :value="old('ville', $client->ville)" required />
                                <x-input-error :messages="$errors->get('ville')" class="mt-2" />
                            </div>

                            <!-- Pays -->
                            <div>
                                <x-input-label for="pays" :value="__('Pays')" class="text-gray-300" />
                                <x-text-input id="pays" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="pays" :value="old('pays', $client->pays)" required />
                                <x-input-error :messages="$errors->get('pays')" class="mt-2" />
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <x-input-label for="notes" :value="__('Notes')" class="text-gray-300" />
                                <textarea id="notes" name="notes" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="3">{{ old('notes', $client->notes) }}</textarea>
                                <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('clients.show', $client) }}" class="btn-action btn-secondary mr-2">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="btn-action btn-primary">
                                {{ __('Mettre à jour') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section pour définir un mot de passe client pour l'accès à l'espace client -->
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl mt-6">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-4">
                        {{ __('Accès Espace Client') }}
                    </h3>
                    <p class="text-gray-300 mb-4">
                        {{ __('Définissez un mot de passe pour permettre au client d\'accéder à l\'espace client et consulter ses projets.') }}
                    </p>

                    <form method="POST" action="{{ route('client.set-password', $client) }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mot de passe -->
                            <div>
                                <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-300" />
                                <x-text-input id="password" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="password" name="password" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirmation du mot de passe -->
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-300" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="password" name="password_confirmation" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="btn-action btn-success">
                                {{ __('Définir le mot de passe') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section pour définir un mot de passe client pour l'accès à l'espace client -->
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl mt-6">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-lg font-medium text-white mb-4">
                        {{ __('Accès Espace Client') }}
                    </h3>
                    <p class="text-gray-300 mb-4">
                        {{ __('Définissez un mot de passe pour permettre au client d\'accéder à l\'espace client et consulter ses projets.') }}
                    </p>

                    <form method="POST" action="{{ route('client.set-password', $client) }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mot de passe -->
                            <div>
                                <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-300" />
                                <x-text-input id="password" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="password" name="password" required />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirmation du mot de passe -->
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-300" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="password" name="password_confirmation" required />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="btn-action btn-success">
                                {{ __('Définir le mot de passe') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>