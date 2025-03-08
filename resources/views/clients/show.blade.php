<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Détails du client') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">{{ $client->nom_complet }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('clients.edit', $client) }}" class="btn-action btn-secondary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                {{ __('Modifier') }}
                            </a>
                            <a href="{{ route('clients.index') }}" class="btn-action btn-primary flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                {{ __('Retour') }}
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Informations générales</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                <div class="mb-2">
                                    <span class="font-semibold">Nom complet:</span> {{ $client->nom_complet }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Société:</span> {{ $client->societe ?? 'Non spécifié' }}
                                </div>
                                <div class="mb-2">
                                    <span class="font-semibold">Email:</span> 
                                    <a href="mailto:{{ $client->email }}" class="text-accent-blue hover:underline">
                                        {{ $client->email }}
                                    </a>
                                </div>
                                <div>
                                    <span class="font-semibold">Téléphone:</span> {{ $client->telephone }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-300 mb-2">Adresse</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                <div class="mb-2">{{ $client->adresse }}</div>
                                <div>{{ $client->code_postal }} {{ $client->ville }}, {{ $client->pays }}</div>
                            </div>
                        </div>

                        @if($client->notes)
                        <div class="md:col-span-2">
                            <h4 class="font-medium text-gray-300 mb-2">Notes</h4>
                            <div class="glassmorphism p-4 rounded-xl text-gray-200 border border-gray-700">
                                {{ $client->notes }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Chantiers du client -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-medium text-gray-300">Chantiers</h4>
                            <a href="{{ route('chantiers.create.step1', ['client_id' => $client->id]) }}" class="px-4 py-2 bg-accent-green text-white rounded-lg hover:bg-green-600 transition duration-150 ease-in-out flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                {{ __('Nouveau chantier') }}
                            </a>
                        </div>
                        
                        @if($client->chantiers->count() > 0)
                            <div class="overflow-x-auto rounded-lg shadow-lg">
                                <table class="min-w-full table-styled">
                                    <thead>
                                        <tr>
                                            <th class="py-3 px-4 text-left">Référence</th>
                                            <th class="py-3 px-4 text-left">Nom</th>
                                            <th class="py-3 px-4 text-left">Date butoir</th>
                                            <th class="py-3 px-4 text-left">État</th>
                                            <th class="py-3 px-4 text-left">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->chantiers as $chantier)
                                            <tr>
                                                <td class="py-3 px-4">{{ $chantier->reference }}</td>
                                                <td class="py-3 px-4">{{ $chantier->nom }}</td>
                                                <td class="py-3 px-4">{{ $chantier->date_butoir->format('d/m/Y') }}</td>
                                                <td class="py-3 px-4">
                                                    @if($chantier->etat == 'non_commence')
                                                        <span class="badge badge-info">Non commencé</span>
                                                    @elseif($chantier->etat == 'en_cours')
                                                        <span class="badge badge-warning">En cours</span>
                                                    @else
                                                        <span class="badge badge-success">Terminé</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-4">
                                                    <a href="{{ route('chantiers.show', $chantier) }}" class="text-accent-blue hover:underline hover:text-blue-400 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        Voir
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="p-10 rounded-xl text-center text-gray-400 bg-gray-800/30 flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="mb-4">Ce client n'a pas encore de chantiers.</p>
                                <a href="{{ route('chantiers.create.step1', ['client_id' => $client->id]) }}" class="btn-action btn-primary flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Créer un chantier
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>