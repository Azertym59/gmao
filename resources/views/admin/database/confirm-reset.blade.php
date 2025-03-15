<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="font-semibold text-xl text-white leading-tight">
                {{ __('Réinitialisation de la base de données') }}
            </h1>
            <a href="{{ route('admin.database.manager') }}" class="btn-action btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la gestion
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-900/50 border border-red-700 text-red-100 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="bg-red-900/20 p-4 border border-red-800/50 rounded-lg mb-6">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <h3 class="text-lg font-medium text-red-400 mb-2">Attention ! Cette action est irréversible</h3>
                                <p class="text-gray-300">Vous êtes sur le point de réinitialiser complètement la base de données. Cette action va :</p>
                                <ul class="list-disc list-inside mt-2 space-y-1 text-gray-300">
                                    <li>Supprimer <span class="text-red-400 font-medium">TOUTES</span> les données existantes</li>
                                    <li>Recréer toutes les tables de la base de données</li>
                                    <li>Créer un nouvel utilisateur administrateur par défaut</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-900/20 p-4 border border-amber-800/50 rounded-lg mb-6">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="text-lg font-medium text-amber-400 mb-2">Avant de continuer</h3>
                                <p class="text-gray-300">Nous vous recommandons fortement de :</p>
                                <ul class="list-disc list-inside mt-2 space-y-1 text-gray-300">
                                    <li>Créer une sauvegarde de la base de données actuelle</li>
                                    <li>Télécharger cette sauvegarde pour une meilleure sécurité</li>
                                    <li>Vérifier que vous avez bien une copie de toutes les données importantes</li>
                                </ul>
                                <p class="mt-2 text-gray-300">Note : Une sauvegarde automatique sera créée avant la réinitialisation, mais nous vous recommandons de créer la vôtre pour plus de sécurité.</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.database.reset') }}" class="mt-6" onsubmit="return confirm('Êtes-vous ABSOLUMENT sûr de vouloir réinitialiser la base de données? Cette action ne peut pas être annulée.')">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="confirm" class="block text-gray-300 text-sm font-medium mb-2">Pour confirmer, tapez "RESET" en majuscules</label>
                            <input type="text" name="confirm" id="confirm" required class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div class="flex gap-4">
                            <a href="{{ route('admin.database.manager') }}" class="btn-action btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn-action btn-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Réinitialiser la base de données
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>