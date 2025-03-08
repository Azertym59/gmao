@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <!-- Ajout d'une section pour le titre et les boutons d'action -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Chantier: {{ $chantier->name }}</h1>
             <!-- Section des boutons d'action -->
             <div class="flex space-x-2">
                <!-- Bouton QR Code -->
                <a href="{{ route('qrcode.chantier.print', $chantier->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v-4m6 0H4m12 6h-2m-6 0H4m12 6h-2m-6 0H4" />
                    </svg>
                    Imprimer QR Code
                </a>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <h2 class="text-lg font-semibold mb-2">Informations Client</h2>
                <p><strong>Client:</strong> {{ $chantier->client->name }}</p>
                <p><strong>Contact:</strong> {{ $chantier->client->contact }}</p>
                <p><strong>Adresse:</strong> {{ $chantier->location }}</p>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold mb-2">Informations Chantier</h2>
                <p><strong>Produits:</strong> {{ $chantier->produits->count() }}</p>
                <p><strong>Dalles:</strong> {{ $chantier->produits->sum(function($produit) { return $produit->dalles->count(); }) }}</p>
                <p><strong>Modules:</strong> {{ $chantier->produits->sum(function($produit) { 
                    return $produit->dalles->sum(function($dalle) { 
                        return $dalle->modules->count(); 
                    }); 
                }) }}</p>
            </div>
        </div>
        
        <h2 class="text-lg font-semibold mb-2">Produits installés</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border">Référence</th>
                        <th class="py-2 px-4 border">Nombre de dalles</th>
                        <th class="py-2 px-4 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chantier->produits as $produit)
                    <tr>
                        <td class="py-2 px-4 border">{{ $produit->reference }}</td>
                        <td class="py-2 px-4 border">{{ $produit->dalles->count() }}</td>
                        <td class="py-2 px-4 border">
                            <a href="{{ route('produits.show', $produit->id) }}" class="text-blue-500 hover:underline">Voir détails</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection