@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">Test HTTP pour l'imprimante : {{ $printer->name }}</h1>
        <p class="text-gray-600">Résultats des tests de connectivité HTTP/HTTPS pour cette imprimante.</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Informations de l'imprimante</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p><strong>Nom :</strong> {{ $printer->name }}</p>
                <p><strong>Modèle :</strong> {{ $printer->model }}</p>
                <p><strong>Adresse IP :</strong> {{ $printer->ip_address }}</p>
                <p><strong>Port configuré :</strong> {{ $printer->port ?: 'Non défini' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Test de connectivité HTTP/HTTPS</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($results as $port => $result)
            <div class="p-4 border rounded {{ $result['success'] ? 'border-green-500 bg-green-50' : 'border-red-500 bg-red-50' }}">
                <h3 class="text-lg font-semibold mb-2">
                    {{ $port == 80 ? 'HTTP (Port 80)' : 'HTTPS (Port 443)' }}
                </h3>
                <p><strong>URL testée :</strong> {{ $result['url'] }}</p>
                <p><strong>Statut :</strong> 
                    <span class="{{ $result['success'] ? 'text-green-600' : 'text-red-600' }}">
                        {{ $result['success'] ? 'Connexion réussie' : 'Échec de connexion' }}
                    </span>
                </p>
                @if(isset($result['http_code']))
                <p><strong>Code HTTP :</strong> {{ $result['http_code'] }}</p>
                @endif
                @if(!empty($result['error']))
                <p><strong>Erreur :</strong> {{ $result['error'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    
    @if(!empty($endpoints))
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Test des endpoints d'impression</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Endpoint</th>
                        <th class="py-2 px-4 border-b text-left">Statut</th>
                        <th class="py-2 px-4 border-b text-left">Code HTTP</th>
                        <th class="py-2 px-4 border-b text-left">URL complète</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($endpoints as $endpoint => $result)
                    <tr class="{{ $result['success'] ? 'bg-green-50' : 'bg-red-50' }}">
                        <td class="py-2 px-4 border-b">{{ $endpoint }}</td>
                        <td class="py-2 px-4 border-b">
                            <span class="{{ $result['success'] ? 'text-green-600' : 'text-red-600' }}">
                                {{ $result['success'] ? 'Accessible' : 'Non accessible' }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b">{{ $result['http_code'] ?? 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $result['url'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Recommandations</h2>
        
        @php
        $hasWorkingPort = false;
        foreach ($results as $port => $result) {
            if ($result['success']) {
                $hasWorkingPort = true;
                $workingPort = $port;
                break;
            }
        }
        @endphp
        
        @if($hasWorkingPort)
            <div class="p-4 bg-green-100 rounded mb-4">
                <p class="text-green-800">
                    <strong>Connexion HTTP réussie sur le port {{ $workingPort }}.</strong>
                </p>
                <p class="mt-2">
                    Assurez-vous que ce port est configuré dans les paramètres de l'imprimante.
                </p>
            </div>
            
            @php
            $workingEndpoints = [];
            if (!empty($endpoints)) {
                foreach ($endpoints as $endpoint => $result) {
                    if ($result['success']) {
                        $workingEndpoints[] = $endpoint;
                    }
                }
            }
            @endphp
            
            @if(!empty($workingEndpoints))
                <div class="p-4 bg-green-100 rounded mb-4">
                    <p class="text-green-800">
                        <strong>Endpoints d'impression accessibles :</strong>
                    </p>
                    <ul class="list-disc pl-6 mt-2">
                        @foreach($workingEndpoints as $endpoint)
                        <li>{{ $endpoint }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-2">
                        Ces endpoints pourraient être utilisés pour l'impression. Essayez de les configurer dans les paramètres du service d'impression.
                    </p>
                </div>
            @else
                <div class="p-4 bg-yellow-100 rounded mb-4">
                    <p class="text-yellow-800">
                        <strong>Aucun endpoint d'impression standard détecté.</strong>
                    </p>
                    <p class="mt-2">
                        Bien que l'imprimante réponde sur HTTP, les endpoints d'impression standards n'ont pas été détectés.
                        Consultez la documentation de votre imprimante pour connaître l'URL exacte pour l'impression.
                    </p>
                </div>
            @endif
        @else
            <div class="p-4 bg-red-100 rounded mb-4">
                <p class="text-red-800">
                    <strong>Aucune connexion HTTP/HTTPS n'a réussi.</strong>
                </p>
                <p class="mt-2">
                    Vérifiez que :
                </p>
                <ul class="list-disc pl-6 mt-1">
                    <li>L'imprimante est allumée et connectée au réseau</li>
                    <li>L'adresse IP configurée ({{ $printer->ip_address }}) est correcte</li>
                    <li>Aucun pare-feu ne bloque les connexions HTTP/HTTPS vers l'imprimante</li>
                    <li>L'interface web de l'imprimante est activée</li>
                </ul>
            </div>
        @endif
    </div>

    <div class="flex space-x-4">
        <a href="{{ route('printers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-md shadow">
            Retour à la liste
        </a>
        
        <a href="{{ route('printers.edit', $printer->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-md shadow">
            Modifier l'imprimante
        </a>
        
        <a href="{{ route('printers.test_http', $printer->id) }}" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-bold rounded-md shadow">
            Relancer le test HTTP
        </a>
        
        <a href="{{ route('printers.direct-print', $printer->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-md shadow">
            Tester l'impression
        </a>
    </div>
</div>
@endsection