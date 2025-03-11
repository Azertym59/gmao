@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestion des imprimantes</h1>
        <a href="{{ route('printers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-plus mr-2"></i> Ajouter une imprimante
        </a>
    </div>

    @if ($message = Session::get('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ $message }}</p>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100 text-gray-800 text-sm uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Nom</th>
                        <th class="px-4 py-3 text-left">Modèle</th>
                        <th class="px-4 py-3 text-left">Connexion</th>
                        <th class="px-4 py-3 text-left">Statut</th>
                        <th class="px-4 py-3 text-left">Format d'étiquette</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm divide-y divide-gray-200">
                    @forelse ($printers as $printer)
                    <tr @if($printer->is_default) class="bg-blue-50" @endif>
                        <td class="px-4 py-3">
                            {{ $printer->name }}
                            @if($printer->is_default) 
                                <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full ml-2">Par défaut</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $printer->model }}</td>
                        <td class="px-4 py-3">
                            @if($printer->connection_type == 'network')
                                Réseau: {{ $printer->ip_address }}:{{ $printer->port }}
                            @elseif($printer->connection_type == 'usb')
                                USB
                            @elseif($printer->connection_type == 'bluetooth')
                                Bluetooth
                            @else
                                {{ $printer->connection_type }}
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($printer->isAvailable())
                                <span class="text-green-600 font-semibold">Disponible</span>
                            @else
                                <span class="text-red-600 font-semibold">Non disponible</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            {{ $printer->label_width }} x {{ $printer->label_height }} mm
                        </td>
                        <td class="px-4 py-3 flex items-center space-x-2">
                            <a href="{{ route('printers.show', $printer->id) }}" class="text-blue-500 hover:text-blue-700" title="Détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('printers.edit', $printer->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('printers.test', $printer->id) }}" class="text-green-500 hover:text-green-700" title="Tester l'impression">
                                <i class="fas fa-print"></i>
                            </a>
                            @if(!$printer->is_default)
                            <form action="{{ route('printers.set-default', $printer->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-blue-500 hover:text-blue-700" title="Définir par défaut">
                                    <i class="fas fa-star"></i>
                                </button>
                            </form>
                            @endif
                            <form action="{{ route('printers.destroy', $printer->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette imprimante?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-print text-gray-300 text-5xl mb-4"></i>
                                <p class="text-gray-500">Aucune imprimante configurée</p>
                                <a href="{{ route('printers.create') }}" class="mt-3 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Configurer une imprimante
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-8 bg-blue-100 rounded-lg p-4 border border-blue-300">
        <h2 class="text-xl font-semibold text-blue-800 mb-2">Comment configurer votre imprimante Brother QL-820NWBc?</h2>
        <ol class="list-decimal list-inside text-gray-700 space-y-2 ml-4">
            <li>Assurez-vous que votre imprimante Brother est connectée au même réseau Wi-Fi que votre ordinateur</li>
            <li>Trouvez l'adresse IP de votre imprimante depuis le menu de l'imprimante ou depuis votre routeur</li>
            <li>Cliquez sur "Ajouter une imprimante" et remplissez les informations (utilisez l'adresse IP trouvée)</li>
            <li>Sélectionnez le modèle "Brother QL-820NWB" et le format d'étiquette DK22251</li>
            <li>Cliquez sur "Tester l'impression" pour vérifier que tout fonctionne correctement</li>
        </ol>
        <p class="mt-4 text-sm text-gray-600">
            Pour plus d'informations, consultez le <a href="https://support.brother.com/g/b/downloadtop.aspx?c=fr&lang=fr&prod=lpql820nwbeuk" class="text-blue-600 underline" target="_blank">manuel d'utilisation Brother</a>
        </p>
    </div>
</div>
@endsection