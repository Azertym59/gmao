<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Modifier le chantier') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <form method="POST" action="{{ route('chantiers.update', $chantier) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Client -->
                            <div>
                                <x-input-label for="client_id" :value="__('Client')" class="text-gray-300" />
                                <select id="client_id" name="client_id" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    @foreach($clients as $client_item)
                                        <option value="{{ $client_item->id }}" {{ (old('client_id', $chantier->client_id) == $client_item->id) ? 'selected' : '' }}>
                                            {{ $client_item->nom_complet }} ({{ $client_item->societe }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                            </div>

                            <!-- Référence (lecture seule) -->
                            <div>
                                <x-input-label for="reference" :value="__('Référence')" class="text-gray-300" />
                                <x-text-input id="reference" class="block mt-1 w-full bg-gray-800 border-gray-700 text-gray-300" type="text" name="reference" :value="$chantier->reference" readonly />
                                <p class="text-sm text-gray-500 mt-1">La référence est générée automatiquement et ne peut pas être modifiée.</p>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description')" class="text-gray-300" />
                                <textarea id="description" name="description" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" rows="3">{{ old('description', $chantier->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <!-- Date de réception -->
                            <div>
                                <x-input-label for="date_reception" :value="__('Date de réception')" class="text-gray-300" />
                                <x-text-input id="date_reception" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="date" name="date_reception" :value="old('date_reception', $chantier->date_reception->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date_reception')" class="mt-2" />
                            </div>

                            <!-- Date butoir -->
                            <div>
                                <x-input-label for="date_butoir" :value="__('Date butoir')" class="text-gray-300" />
                                <x-text-input id="date_butoir" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" type="date" name="date_butoir" :value="old('date_butoir', $chantier->date_butoir->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date_butoir')" class="mt-2" />
                            </div>

                            <!-- État -->
                            <div>
                                <x-input-label for="etat" :value="__('État')" class="text-gray-300" />
                                <select id="etat" name="etat" class="block mt-1 w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                                    <option value="non_commence" {{ old('etat', $chantier->etat) == 'non_commence' ? 'selected' : '' }}>Non commencé</option>
                                    <option value="en_cours" {{ old('etat', $chantier->etat) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="termine" {{ old('etat', $chantier->etat) == 'termine' ? 'selected' : '' }}>Terminé</option>
                                </select>
                                <x-input-error :messages="$errors->get('etat')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('chantiers.show', $chantier) }}" class="btn-action btn-secondary mr-2">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="btn-action btn-primary">
                                {{ __('Mettre à jour') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>