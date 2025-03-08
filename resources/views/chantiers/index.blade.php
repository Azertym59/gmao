<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-white leading-tight">
            {{ __('Chantiers') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">Liste des chantiers</h3>
                        <a href="{{ route('chantiers.create.step1') }}" class="px-4 py-2 bg-accent-blue text-white rounded-lg hover:bg-blue-600 transition duration-150 ease-in-out flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter un chantier
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-500/30 border border-green-500/50 text-green-400 rounded-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg shadow-lg">
                        <table class="min-w-full table-styled border border-gray-700 bg-gray-800/50 text-gray-300">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Référence</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Client</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Nom</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Date butoir</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">État</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($chantiers as $chantier)
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition duration-200">
                                        <td class="py-3 px-4">{{ $chantier->reference }}</td>
                                        <td class="py-3 px-4">{{ $chantier->client->nom_complet }}</td>
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
                                        <td class="py-3 px-4 flex gap-2">
                                            <a href="{{ route('chantiers.show', $chantier) }}" 
                                               class="btn-action btn-primary flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Voir
                                            </a>
                                            <a href="{{ route('chantiers.edit', $chantier) }}" 
                                               class="btn-action btn-secondary flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Modifier
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-6 px-4 text-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p>Aucun chantier trouvé. Commencez par en créer un !</p>
                                            <a href="{{ route('chantiers.create.step1') }}" class="inline-block mt-3 px-4 py-2 bg-accent-blue text-white rounded-lg hover:bg-blue-600 transition duration-150 ease-in-out">
                                                Créer mon premier chantier
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($chantiers) && method_exists($chantiers, 'links'))
                        <div class="mt-6">
                            {{ $chantiers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>