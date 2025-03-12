@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-2">Test d'impression : {{ $printer->name }}</h1>
        <p class="text-gray-600">Cette page vous permet de tester l'impression sur l'imprimante configurée.</p>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Informations de l'imprimante</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p><strong>Nom :</strong> {{ $printer->name }}</p>
                <p><strong>Modèle :</strong> {{ $printer->model }}</p>
                <p><strong>Adresse IP :</strong> {{ $printer->ip_address }}</p>
                <p><strong>Port :</strong> {{ $printer->port }}</p>
            </div>
            <div>
                <p><strong>Largeur d'étiquette :</strong> {{ $printer->label_width }} mm</p>
                <p><strong>Hauteur d'étiquette :</strong> {{ $printer->label_height }} mm</p>
                <p><strong>Par défaut :</strong> {{ $printer->is_default ? 'Oui' : 'Non' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Options d'impression</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium mb-3">Impression directe</h3>
                <p class="mb-4">Envoie directement à l'imprimante réseau sans passer par la boîte de dialogue Windows.</p>
                
                <a href="{{ isset($directPrintUrl) ? $directPrintUrl : route('printers.direct-print', $printer->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-md shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z" />
                    </svg>
                    Imprimer directement
                </a>
                <a href="{{ route('printers.test_http', $printer->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-bold rounded-md shadow ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012-2v-1a2 2 0 012-2h1.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h.5A2.5 2.5 0 0020 5.5V3.935M8 3.935V2.5A2.5 2.5 0 0110.5 0h4A2.5 2.5 0 0117 2.5v1.435" />
                </svg>
                Test HTTP/HTTPS
                 </a>
                 <a href="{{ route('printers.test-brother', $printer->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-md shadow">
                Tester avec protocole Brother
                </a>
            </div>
            
            <div>
                <h3 class="text-lg font-medium mb-3">Impression via navigateur</h3>
                <p class="mb-4">Ouvre la boîte de dialogue d'impression du navigateur (pour les tests uniquement).</p>
                
                <div id="label-content" class="border border-gray-300 p-4 mb-4" style="width: {{ $printer->label_width }}mm; height: {{ $printer->label_height }}mm;">
                    <div class="text-center">
                        <h3 class="text-lg font-bold mb-2">TEST D'IMPRESSION</h3>
                        <p class="mb-2">Imprimante : {{ $printer->name }}</p>
                        <p class="mb-2">Date : {{ date('d/m/Y H:i:s') }}</p>
                        
                        <div class="qr-container inline-block p-2 border border-gray-400 mt-2">
                            {!! QrCode::size(80)->generate('Test QR Code - ' . $printer->name) !!}
                        </div>
                    </div>
                </div>
                
                {!! App\Services\PrinterService::printButton('Imprimer via navigateur') !!}
            </div>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-200">
            <a href="{{ route('printers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-md shadow">
                Retour à la liste
            </a>
        </div>
    </div>
</div>

<script>
    {!! App\Services\PrinterService::generatePrintScript() !!}
</script>
@endsection