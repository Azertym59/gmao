@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="bg-white p-6 rounded-lg shadow-lg border-2 border-gray-300 max-w-lg mx-auto">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold">Dalle: {{ $dalle->reference }}</h1>
            <p class="text-gray-600">Chantier: {{ $dalle->produit->chantier->name }}</p>
            <p class="text-gray-600">Client: {{ $dalle->produit->chantier->client->name }}</p>
        </div>
        
        <div class="flex justify-center mb-4">
            {!! $qrCode !!}
        </div>
        
        <div class="text-center mb-4">
            <p><strong>Produit:</strong> {{ $dalle->produit->reference }}</p>
            <p><strong>Carte Réception:</strong> {{ $dalle->carte_reception ?? 'N/A' }}</p>
            <p><strong>Hub:</strong> {{ $dalle->hub ?? 'N/A' }}</p>
        </div>
        
        <div class="text-center text-xs text-gray-500">
            <p>Scannez ce QR code pour voir les détails de la dalle</p>
        </div>
    </div>
    
    <div class="text-center mt-6">
        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Imprimer l'étiquette
        </button>
        <a href="{{ route('dalles.show', $dalle->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
            Retour
        </a>
    </div>
</div>
@endsection