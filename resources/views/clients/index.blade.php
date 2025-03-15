<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-text-primary leading-tight">
            {{ __('Clients') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <!-- En-tête avec titre et bouton d'action -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 px-2">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-xl font-semibold text-text-primary flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-accent-purple" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Liste des clients</span>
                    </h2>
                    <p class="text-text-secondary mt-1">Gérez vos clients et leurs informations</p>
                </div>
                <div>
                    <a href="{{ route('clients.create') }}" class="btn bg-white text-gray-900 hover:bg-gray-100 hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter un client
                    </a>
                </div>
            </div>

            <!-- Message de succès -->
            @if (session('success'))
                <div class="alert alert-success">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ session('success') }}
                    </div>
                    <button onclick="this.parentElement.style.display = 'none'" class="text-green-400 hover:text-green-300">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            
            <!-- Tableau des clients -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table-styled">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Société</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clients as $client)
                                    <tr>
                                        <td class="font-medium">{{ $client->nom_complet }}</td>
                                        <td>{{ $client->societe }}</td>
                                        <td>
                                            <a href="mailto:{{ $client->email }}" class="text-accent-blue hover:underline flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ $client->email }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="tel:{{ $client->telephone }}" class="text-text-primary hover:text-accent-blue flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                {{ $client->telephone }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="flex gap-2">
                                                <a href="{{ route('clients.show', $client) }}" 
                                                   class="btn-action bg-accent-purple/10 text-accent-purple hover:bg-accent-purple/20">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Voir
                                                </a>
                                                <a href="{{ route('clients.edit', $client) }}" 
                                                   class="btn-action bg-gray-600/10 text-gray-300 hover:bg-gray-600/20">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Modifier
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-3">
                                                <div class="bg-gray-800 rounded-full p-3 w-16 h-16 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-text-secondary">Aucun client trouvé</p>
                                                    <p class="text-text-secondary text-sm mt-1">Commencez par en créer un</p>
                                                </div>
                                                <a href="{{ route('clients.create') }}" class="btn bg-white text-gray-900 hover:bg-gray-100 hover:scale-105 transition-transform mt-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Créer mon premier client
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if(isset($clients) && method_exists($clients, 'links'))
                        <div class="p-4 border-t border-border-dark">
                            {{ $clients->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>