<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-white leading-tight">
            {{ __('Interventions') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">Liste des interventions</h3>
                        <a href="{{ route('interventions.create') }}" class="px-4 py-2 bg-accent-yellow text-white rounded-lg hover:bg-yellow-500 transition duration-150 ease-in-out flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nouvelle intervention
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

                    <!-- Filtres -->
                    <div class="mb-6 p-4 glassmorphism rounded-lg border border-gray-700">
                        <h4 class="text-white font-medium mb-3">Filtres</h4>
                        <form action="{{ route('interventions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="statut" class="block text-sm font-medium text-gray-400 mb-1">Statut</label>
                                <select id="statut" name="statut" class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tous les statuts</option>
                                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="terminé" {{ request('statut') == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                </select>
                            </div>
                            <div>
                                <label for="technicien" class="block text-sm font-medium text-gray-400 mb-1">Technicien</label>
                                <select id="technicien" name="technicien" class="w-full rounded-md bg-gray-700 border-gray-600 text-white focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Tous les techniciens</option>
                                    @foreach($techniciens ?? [] as $tech)
                                        <option value="{{ $tech->id }}" {{ request('technicien') == $tech->id ? 'selected' : '' }}>{{ $tech->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition duration-150 ease-in-out">
                                    Filtrer
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto rounded-lg shadow-lg">
                        <table class="min-w-full table-styled border border-gray-700 bg-gray-800/50 text-gray-300">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Date</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Technicien</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Module</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Chantier</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Statut</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($interventions ?? [] as $intervention)
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition duration-200">
                                        <td class="py-3 px-4">{{ $intervention->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="py-3 px-4">{{ $intervention->user->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="inline-block px-2 py-1 bg-gray-700 rounded text-xs font-medium">
                                                {{ $intervention->module->reference ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">{{ $intervention->module->dalle->produit->chantier->nom ?? 'N/A' }}</td>
                                        <td class="py-3 px-4">
                                            @if($intervention->statut == 'terminé')
                                                <span class="badge badge-success">Terminé</span>
                                            @elseif($intervention->statut == 'en_cours')
                                                <span class="badge badge-warning">En cours</span>
                                            @else
                                                <span class="badge badge-info">En attente</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 flex gap-2">
                                            <a href="{{ route('interventions.show', $intervention) }}" 
                                               class="btn-action btn-primary flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Voir
                                            </a>
                                            <a href="{{ route('interventions.edit', $intervention) }}" 
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                            <p>Aucune intervention trouvée.</p>
                                            <a href="{{ route('interventions.create') }}" class="inline-block mt-3 px-4 py-2 bg-accent-yellow text-white rounded-lg hover:bg-yellow-500 transition duration-150 ease-in-out">
                                                Créer une intervention
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($interventions) && method_exists($interventions, 'links'))
                        <div class="mt-6">
                            {{ $interventions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>