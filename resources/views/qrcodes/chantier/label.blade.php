@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4 px-4">
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 max-w-2xl mx-auto">
        <!-- En-tête avec logo et titre -->
        <div class="bg-black text-white p-4 flex items-center">
            <!-- Logo TecaLED à gauche -->
            <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair.png" alt="TecaLED Logo" class="h-14 mr-4">
            <!-- Titre à droite -->
            <h1 class="text-2xl font-bold">FICHE CHANTIER</h1>
        </div>
        
        <!-- Section Informations -->
        <div class="p-6">
            <h2 class="text-2xl font-bold text-black mb-4 border-b border-blue-500 pb-2">Informations</h2>
            
            <div class="grid grid-cols-2 gap-4">
                <!-- Client -->
                <div class="bg-gray-100 p-4 rounded-md border border-gray-300">
                    <p class="font-bold text-black">Client:</p>
                    <p class="text-black">
                        @if(isset($chantier->client) && isset($chantier->client->name))
                            {{ $chantier->client->name }}
                        @elseif(isset($chantier->client) && isset($chantier->client->societe))
                            {{ $chantier->client->societe }}
                        @else
                            Non défini
                        @endif
                    </p>
                </div>
                
                <!-- Référence chantier -->
                <div class="bg-gray-100 p-4 rounded-md border border-gray-300">
                    <p class="font-bold text-black">Référence chantier:</p>
                    <p class="text-black">
                        @if(isset($chantier->reference))
                            {{ $chantier->reference }}
                        @else
                            GMAO-{{ date('Ymd') }}-{{ str_pad($chantier->id, 3, '0', STR_PAD_LEFT) }}
                        @endif
                    </p>
                </div>
                
                <!-- Adresse -->
                <div class="bg-gray-100 p-4 rounded-md border border-gray-300">
                    <p class="font-bold text-black">Adresse:</p>
                    <p class="text-black">
                        @if(isset($chantier->adresse) && !empty($chantier->adresse))
                            {{ $chantier->adresse }}
                        @elseif(isset($chantier->address) && !empty($chantier->address))
                            {{ $chantier->address }}
                        @elseif(isset($chantier->location) && !empty($chantier->location))
                            {{ $chantier->location }}
                        @else
                            Non définie
                        @endif
                    </p>
                </div>
                
                <!-- Date butoir -->
                <div class="bg-gray-100 p-4 rounded-md border border-gray-300">
                    <p class="font-bold text-black">Date butoir:</p>
                    <p class="text-black">
                        @if(isset($chantier->deadline) && !empty($chantier->deadline))
                            {{ date('d/m/Y', strtotime($chantier->deadline)) }}
                        @elseif(isset($chantier->date_butoir) && !empty($chantier->date_butoir))
                            {{ date('d/m/Y', strtotime($chantier->date_butoir)) }}
                        @else
                            Non définie
                        @endif
                    </p>
                </div>
                
                <!-- Date de création -->
                <div class="bg-gray-100 p-4 rounded-md border border-gray-300">
                    <p class="font-bold text-black">Date de création:</p>
                    <p class="text-black">{{ date('d/m/Y', strtotime($chantier->created_at)) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Ligne de séparation -->
        <hr class="border-t border-gray-300">
        
        <!-- Section Composition et QR code -->
        <div class="grid grid-cols-2 gap-4 p-6">
            <!-- Composition -->
            <div>
                <h2 class="text-2xl font-bold text-black mb-4 border-b border-blue-500 pb-2">Composition</h2>
                
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="border border-blue-500 rounded-md p-3 text-center">
                        <p class="text-black font-semibold">Produits</p>
                        <p class="text-black text-3xl font-bold">
                            @if(isset($chantier->produits))
                                {{ $chantier->produits->count() }}
                            @else
                                0
                            @endif
                        </p>
                    </div>
                    
                    <div class="border border-blue-500 rounded-md p-3 text-center">
                        <p class="text-black font-semibold">Dalles</p>
                        <p class="text-black text-3xl font-bold">
                            @if(isset($chantier->produits))
                                {{ $chantier->produits->sum(function($produit) { 
                                    return isset($produit->dalles) ? $produit->dalles->count() : 0; 
                                }) }}
                            @else
                                0
                            @endif
                        </p>
                    </div>
                    
                    <div class="border border-blue-500 rounded-md p-3 text-center">
                        <p class="text-black font-semibold">Modules</p>
                        <p class="text-black text-3xl font-bold">
                            @if(isset($chantier->produits))
                                {{ $chantier->produits->sum(function($produit) { 
                                    return isset($produit->dalles) ? $produit->dalles->sum(function($dalle) { 
                                        return isset($dalle->modules) ? $dalle->modules->count() : 0; 
                                    }) : 0; 
                                }) }}
                            @else
                                0
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Références produit -->
                <div class="bg-gray-100 p-4 rounded-md border border-gray-300">
                    <p class="font-bold text-black mb-1">Références produit:</p>
                    <ul class="list-disc pl-6 text-black">
                        @if(isset($chantier->produits) && $chantier->produits->count() > 0)
                            @foreach($chantier->produits as $produit)
                                <li>
                                    @if(isset($produit->reference) && !empty($produit->reference))
                                        {{ $produit->reference }}
                                    @elseif(isset($produit->ref) && !empty($produit->ref))
                                        {{ $produit->ref }}
                                    @elseif(isset($produit->name) && !empty($produit->name))
                                        {{ $produit->name }}
                                    @else
                                        Produit sans référence
                                    @endif
                                </li>
                            @endforeach
                        @else
                            <li>Produit sans référence</li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <!-- QR Code -->
            <div class="bg-gray-100 p-4 rounded-md flex flex-col items-center justify-center">
                <div class="bg-white p-4 border border-black">
                    {!! $qrCode !!}
                </div>
                <p class="text-center text-black mt-4">Scannez ce QR code pour voir les détails du chantier</p>
                <p class="text-center text-black text-sm mt-1">Généré le {{ date('d/m/Y') }}</p>
            </div>
        </div>
        
        <!-- Pied de page -->
        <div class="bg-gray-100 p-3 text-center border-t border-gray-300">
            <p class="text-black">GMAO TecaLED - {{ date('Y') }}</p>
        </div>
    </div>
    
    <!-- Boutons d'action -->
    <div class="text-center mt-6 space-x-2">
        <button onclick="window.print()" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Imprimer l'étiquette
        </button>
        <a href="{{ route('chantiers.show', $chantier->id) }}" class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
        </a>
    </div>
</div>

<style>
    /* Styles pour le QR code */
    svg {
        max-width: 100%;
        height: auto;
    }
    
    /* Styles d'impression */
    @media print {
        body {
            background-color: white;
        }
        .container {
            max-width: 100%;
            padding: 0;
        }
        button, a {
            display: none !important;
        }
    }
</style>
@endsection