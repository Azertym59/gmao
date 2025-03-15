<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-white leading-tight">
            {{ __('Configuration de l\'administrateur') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="bg-green-900/20 p-4 border border-green-800/50 rounded-lg mb-6">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h3 class="text-lg font-medium text-green-400 mb-2">Base de données réinitialisée avec succès</h3>
                                <p class="text-gray-300">La base de données a été correctement réinitialisée. Vous devez maintenant créer un compte administrateur pour accéder à l'application.</p>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold text-white mb-6">Créer un compte administrateur</h2>

                    <form method="POST" action="{{ route('admin.database.store-admin') }}">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="name" class="block text-gray-300 text-sm font-medium mb-2">Nom</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name" autofocus 
                                   class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="email" class="block text-gray-300 text-sm font-medium mb-2">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                                   class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="password" class="block text-gray-300 text-sm font-medium mb-2">Mot de passe</label>
                            <input type="password" name="password" id="password" required autocomplete="new-password"
                                   class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-gray-300 text-sm font-medium mb-2">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                                   class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div class="flex items-center justify-end">
                            <button type="submit" class="btn-action btn-success">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Créer le compte administrateur
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>