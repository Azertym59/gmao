<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestion de la base de données') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-900/50 border border-green-700 text-green-100 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-900/50 border border-red-700 text-red-100 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl mb-6">
                <div class="p-6 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white mb-4">Informations sur la base de données</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-blue-900/30 p-4 rounded-lg border border-blue-500/30">
                            <h3 class="text-blue-300 text-sm font-medium mb-2">Type de base de données</h3>
                            <p class="text-white font-medium">SQLite</p>
                        </div>

                        <div class="bg-purple-900/30 p-4 rounded-lg border border-purple-500/30">
                            <h3 class="text-purple-300 text-sm font-medium mb-2">Taille de la base de données</h3>
                            <p class="text-white font-medium">{{ $dbSize }} MB</p>
                        </div>
                    </div>

                    <div class="bg-gray-900/30 p-6 rounded-lg border border-gray-600/30 mb-6">
                        <h3 class="text-gray-100 text-md font-medium mb-4">Tables ({{ count($tables) }})</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach($tables as $table)
                                <div class="bg-gray-800/50 p-2 rounded text-sm text-gray-300">
                                    {{ $table->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <form method="POST" action="{{ route('admin.database.backup') }}" class="inline">
                            @csrf
                            <button type="submit" class="btn-action btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Sauvegarder la base de données
                            </button>
                        </form>

                        <a href="{{ route('admin.database.backups') }}" class="btn-action btn-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                            </svg>
                            Gérer les sauvegardes
                        </a>

                        <a href="{{ route('admin.database.confirm-reset') }}" class="btn-action btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Réinitialiser la base de données
                        </a>
                    </div>
                </div>
            </div>

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white mb-4">Pourquoi gérer votre base de données ?</h2>
                    
                    <div class="space-y-4 text-gray-300">
                        <div class="flex">
                            <div class="flex-shrink-0 bg-indigo-800/50 p-1 rounded-md mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white text-md font-medium mb-1">Protection des données</h3>
                                <p>Les sauvegardes régulières protègent contre la perte de données en cas de défaillance technique.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0 bg-green-800/50 p-1 rounded-md mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white text-md font-medium mb-1">Maintenance simplifiée</h3>
                                <p>Réinitialisez rapidement votre environnement de production en cas de besoin tout en conservant les sauvegardes des données importantes.</p>
                            </div>
                        </div>
                        
                        <div class="flex">
                            <div class="flex-shrink-0 bg-amber-800/50 p-1 rounded-md mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white text-md font-medium mb-1">Récupération rapide</h3>
                                <p>Restaurez rapidement votre système à un état antérieur fonctionnel en cas de problème.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>