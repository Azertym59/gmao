@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gestion des imprimantes</h1>
        <div class="flex space-x-2">
            <a href="{{ route('printers.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                Ajouter une imprimante
            </a>
            <a href="{{ route('printers.test-qz-tray') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                Tester QZ Tray
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nom
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Statut
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Par défaut
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($printers as $printer)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $printer->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ ucfirst($printer->type ?? 'Standard') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $printer->status == 'online' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $printer->status == 'online' ? 'En ligne' : 'Hors ligne' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($printer->is_default)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Par défaut
                        </span>
                        @else
                        <form action="{{ route('printers.set-default', $printer) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-900 text-xs">Définir par défaut</button>
                        </form>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('printers.edit', $printer) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                            <a href="{{ route('printers.test', $printer) }}" class="text-green-600 hover:text-green-900">Tester</a>
                            <form action="{{ route('printers.destroy', $printer) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette imprimante?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Aucune imprimante configurée. <a href="{{ route('printers.create') }}" class="text-blue-600 hover:underline">Ajouter une imprimante</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Tests d'impression</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                <h3 class="font-semibold mb-2">Test QZ Tray</h3>
                <p class="text-sm text-gray-600 mb-4">Tester la connexion avec QZ Tray et l'impression d'étiquettes via votre navigateur.</p>
                <a href="{{ route('printers.test-qz-tray') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 px-3 rounded">
                    Lancer le test
                </a>
            </div>
            
            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                <h3 class="font-semibold mb-2">Test Brother QL-820NW</h3>
                <p class="text-sm text-gray-600 mb-4">Tester l'impression d'étiquettes standard pour modules, dalles et flightcases.</p>
                <a href="{{ route('printers.test-labels') }}" class="bg-purple-500 hover:bg-purple-600 text-white text-sm py-2 px-3 rounded">
                    Tester les étiquettes
                </a>
            </div>
            
            <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                <h3 class="font-semibold mb-2">Diagnostic HTTP</h3>
                <p class="text-sm text-gray-600 mb-4">Vérifier si l'imprimante est accessible via HTTP pour les diagnostics avancés.</p>
                <button onclick="alert('Sélectionnez d\'abord une imprimante dans la liste')" class="bg-gray-500 hover:bg-gray-600 text-white text-sm py-2 px-3 rounded">
                    Vérifier connexion
                </button>
            </div>
        </div>
    </div>
</div>
@endsection