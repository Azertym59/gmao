@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="mb-4">
        <h1 class="text-2xl font-bold">Test d'impression d'étiquettes</h1>
        <p class="text-gray-600">Cette page vous permet de tester l'impression des différents formats d'étiquettes</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Test Étiquette Module -->
        <div class="glassmorphism rounded-lg overflow-hidden shadow-lg">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-xl font-semibold">Étiquette Module (29mm)</h2>
                <p class="text-sm text-gray-400">Format compact pour les modules individuels</p>
            </div>
            
            <div class="p-4 flex flex-col items-center">
                <div class="bg-white p-2 rounded border border-gray-300 mb-4" style="width: 100px; height: 42px;">
                    <img src="{{ $moduleQR }}" alt="QR Code Module" class="w-full">
                </div>
                
                <button onclick="window.open('{{ route('etiquettes.test.module') }}', '_blank')" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Tester impression
                </button>
            </div>
        </div>

        <!-- Test Étiquette Dalle -->
        <div class="glassmorphism rounded-lg overflow-hidden shadow-lg">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-xl font-semibold">Étiquette Dalle (38mm)</h2>
                <p class="text-sm text-gray-400">Format moyen pour les dalles</p>
            </div>
            
            <div class="p-4 flex flex-col items-center">
                <div class="bg-white p-2 rounded border border-gray-300 mb-4" style="width: 124px; height: 65px;">
                    <img src="{{ $dalleQR }}" alt="QR Code Dalle" class="w-full">
                </div>
                
                <button onclick="window.open('{{ route('etiquettes.test.dalle') }}', '_blank')" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Tester impression
                </button>
            </div>
        </div>

        <!-- Test Étiquette FlightCase -->
        <div class="glassmorphism rounded-lg overflow-hidden shadow-lg">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-xl font-semibold">Étiquette FlightCase (62mm)</h2>
                <p class="text-sm text-gray-400">Format grand pour les chantiers</p>
            </div>
            
            <div class="p-4 flex flex-col items-center">
                <div class="bg-white p-2 rounded border border-gray-300 mb-4" style="width: 150px; height: 150px;">
                    <img src="{{ $chantierQR }}" alt="QR Code Chantier" class="w-full">
                </div>
                
                <button onclick="window.open('{{ route('etiquettes.test.flightcase') }}', '_blank')" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Tester impression
                </button>
            </div>
        </div>
    </div>

    <div class="mt-8 glassmorphism rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-2">Instructions d'impression</h3>
        
        <div class="space-y-4">
            <div class="bg-blue-900/30 border border-blue-400/30 rounded-lg p-4">
                <h4 class="font-bold mb-2">Configuration d'imprimante recommandée</h4>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Imprimante Brother QL-820NWB ou compatible</li>
                    <li>Rouleau continu DK-22205 (62mm)</li>
                    <li>Résolution 300 DPI</li>
                    <li>Largeur de coupe ajustée selon le format d'étiquette</li>
                </ul>
            </div>
            
            <div class="bg-yellow-900/30 border border-yellow-400/30 rounded-lg p-4">
                <h4 class="font-bold mb-2">Conseils pour l'impression</h4>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Utilisez le navigateur Chrome pour de meilleurs résultats d'impression</li>
                    <li>Dans la boîte de dialogue d'impression, sélectionnez "Aucune marge" si disponible</li>
                    <li>Désactivez les en-têtes et pieds de page dans les paramètres d'impression</li>
                    <li>Pour les imprimantes Brother, utilisez le pilote d'impression "P-touch Editor"</li>
                </ul>
            </div>
            
            <div class="bg-green-900/30 border border-green-400/30 rounded-lg p-4">
                <h4 class="font-bold mb-2">Résolution des problèmes</h4>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Si l'étiquette est tronquée, vérifiez les paramètres de mise en page et de marges</li>
                    <li>Si le QR code ne s'imprime pas correctement, essayez une taille plus grande</li>
                    <li>Si les couleurs sont incorrectes, vérifiez que l'impression en couleur est activée</li>
                    <li>Pour un QR code plus visible, assurez-vous que l'imprimante a suffisamment d'encre/toner</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection