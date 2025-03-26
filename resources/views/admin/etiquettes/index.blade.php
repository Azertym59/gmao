<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestion des modèles d\'étiquettes') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">Liste des modèles d'étiquettes</h3>
                        <a href="{{ route('admin.etiquettes.create') }}" class="px-4 py-2 bg-accent-yellow text-white rounded-lg hover:bg-yellow-500 transition duration-150 ease-in-out flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Créer un modèle
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

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-500/30 border border-red-500/50 text-red-400 rounded-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg shadow-lg">
                        <table class="min-w-full table-styled border border-gray-700 bg-gray-800/50 text-gray-300">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Nom</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Type</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Taille</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Par défaut</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Date de création</th>
                                    <th class="py-3 px-4 text-left border-b border-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($templates as $template)
                                    <tr class="border-b border-gray-700 hover:bg-gray-700/30 transition duration-200">
                                        <td class="py-3 px-4">{{ $template->name }}</td>
                                        <td class="py-3 px-4">
                                            @if ($template->type === 'chantier')
                                                <span class="badge badge-success">Chantier</span>
                                            @elseif ($template->type === 'dalle')
                                                <span class="badge badge-primary">Dalle</span>
                                            @elseif ($template->type === 'module')
                                                <span class="badge badge-info">Module</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">{{ $template->width }}mm x {{ $template->height }}mm</td>
                                        <td class="py-3 px-4">
                                            @if ($template->is_default)
                                                <span class="text-green-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                            @else
                                                <form method="POST" action="{{ route('admin.etiquettes.make-default', $template) }}">
                                                    @csrf
                                                    <button type="submit" class="btn-action btn-info text-xs">Définir par défaut</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">{{ $template->created_at->format('d/m/Y') }}</td>
                                        <td class="py-3 px-4 flex gap-2">
                                            <a href="{{ route('admin.etiquettes.preview', $template) }}" 
                                               class="btn-action bg-blue-600 hover:bg-blue-700 text-white flex items-center" target="_blank">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Aperçu
                                            </a>
                                            
                                            <a href="{{ route('admin.etiquettes.edit', $template) }}" 
                                               class="btn-action btn-primary flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Modifier
                                            </a>
                                            
                                            <form method="POST" action="{{ route('admin.etiquettes.destroy', $template) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce modèle d\'étiquette?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action bg-red-600 hover:bg-red-700 text-white flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>