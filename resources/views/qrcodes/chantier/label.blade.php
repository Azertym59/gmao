@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="bg-white p-6 rounded-lg shadow-lg border-2 border-gray-300 max-w-lg mx-auto">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold">Chantier: {{ $chantier->name }}</h1>
            <p class="text-gray-600">{{ $chantier->client->name }}</p>
            <p class="text-gray-600">{{ $chantier->location }}</p>
        </div>
        
        <div class="flex justify-center mb-4">
            {!! $qrCode !!}
        </div>
        
        <div class="text-center mb-4">
            <p><strong>Produit:</strong> {{ $chantier->produits->count() > 0 ? $chantier->produits->first()->reference : 'N/A' }}</p>
            <p><strong>Dalles:</strong> {{ $chantier->produits->sum(function($produit) { return $produit->dalles->count(); }) }}</p>
            <p><strong>Modules:</strong> {{ $chantier->produits->sum(function($produit) { 
                return $produit->dalles->sum(function($dalle) { 
                    return $dalle->modules->count(); 
                }); 
            }) }}</p>
        </div>
        
        <div class="text-center text-xs text-gray-500">
            <p>Scannez ce QR code pour voir les détails du chantier</p>
        </div>
    </div>
    
    <div class="text-center mt-6">
        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Imprimer l'étiquette
        </button>
        <a href="{{ route('chantiers.show', $chantier->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
            Retour
        </a>
    </div>
</div>
@endsection