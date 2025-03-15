@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4 px-4">
    <div class="bg-white rounded-lg shadow-lg border border-black max-w-md mx-auto label-container">
        <!-- En-tête compact -->
        <div class="bg-black text-white p-2 flex items-center justify-between">
            <!-- Logo TecaLED à gauche -->
            <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20flag%20repair.png" alt="TecaLED Logo" class="h-8">
            <!-- Titre à droite -->
            <h1 class="text-xl font-bold print-red">FICHE CHANTIER</h1>
        </div>
        
        <!-- Section Référence + QR -->
        <div class="flex p-2 border-b border-black">
            <!-- Référence chantier - en rouge -->
            <div class="w-1/2 mr-2">
                <p class="font-bold text-xs text-black">Référence:</p>
                <p class="text-lg font-bold print-red">
                    @if(isset($chantier->reference))
                        {{ $chantier->reference }}
                    @else
                        GMAO-{{ str_pad($chantier->id, 3, '0', STR_PAD_LEFT) }}
                    @endif
                </p>
                <p class="text-xs mt-1 text-black">
                    Créé le: {{ date('d/m/Y', strtotime($chantier->created_at)) }}
                </p>
                <p class="text-xs mt-1 print-red font-bold">
                    @if(isset($chantier->deadline) && !empty($chantier->deadline))
                        Butoir: {{ date('d/m/Y', strtotime($chantier->deadline)) }}
                    @elseif(isset($chantier->date_butoir) && !empty($chantier->date_butoir))
                        Butoir: {{ date('d/m/Y', strtotime($chantier->date_butoir)) }}
                    @endif
                </p>
            </div>
            
            <!-- QR Code - Toujours en noir pour meilleure lisibilité -->
            <div class="w-1/2 flex justify-center items-center">
                <div class="bg-white p-1 border border-black qr-container">
                    <img src="{{ $printData['imageData'] }}" alt="QR Code" class="w-full">
                </div>
            </div>
        </div>
        
        <!-- Client et adresse - compact -->
        <div class="p-2 border-b border-black">
            <div class="flex justify-between">
                <div class="w-1/2 pr-1">
                    <p class="font-bold text-xs text-black">Client:</p>
                    <p class="text-sm text-black">
                        @if(isset($chantier->client) && isset($chantier->client->name))
                            {{ $chantier->client->name }}
                        @elseif(isset($chantier->client) && isset($chantier->client->societe))
                            {{ $chantier->client->societe }}
                        @else
                            Non défini
                        @endif
                    </p>
                </div>
                <div class="w-1/2 pl-1">
                    <p class="font-bold text-xs text-black">Adresse:</p>
                    <p class="text-xs text-black">
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
            </div>
        </div>
        
        <!-- Composition - Compact avec compteurs -->
        <div class="p-2">
            <h2 class="text-sm font-bold text-black mb-2">Composition</h2>
            <div class="flex justify-between text-center">
                <div class="border border-black rounded-sm p-1 w-1/3 mx-1">
                    <p class="text-xs font-semibold text-black">Produits</p>
                    <p class="text-lg font-bold text-black">
                        @if(isset($chantier->produits))
                            {{ $chantier->produits->count() }}
                        @else
                            0
                        @endif
                    </p>
                </div>
                
                <div class="border border-black rounded-sm p-1 w-1/3 mx-1">
                    <p class="text-xs font-semibold text-black">Dalles</p>
                    <p class="text-lg font-bold text-black">
                        @if(isset($chantier->produits))
                            {{ $chantier->produits->sum(function($produit) { 
                                return isset($produit->dalles) ? $produit->dalles->count() : 0; 
                            }) }}
                        @else
                            0
                        @endif
                    </p>
                </div>
                
                <div class="border border-black rounded-sm p-1 w-1/3 mx-1">
                    <p class="text-xs font-semibold text-black">Modules</p>
                    <p class="text-lg font-bold text-black">
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
            @if(isset($chantier->produits) && $chantier->produits->count() > 0)
                <div class="mt-2 border-t border-black pt-1">
                    <p class="text-xs font-semibold text-black">Références produit:</p>
                    <div class="text-xs text-black">
                        @foreach($chantier->produits as $produit)
                            <span class="inline-block mr-1">
                                @if(isset($produit->reference) && !empty($produit->reference))
                                    {{ $produit->reference }}
                                @elseif(isset($produit->ref) && !empty($produit->ref))
                                    {{ $produit->ref }}
                                @elseif(isset($produit->produit_reference) && !empty($produit->produit_reference))
                                    {{ $produit->produit_reference }}
                                @elseif(isset($produit->code) && !empty($produit->code))
                                    {{ $produit->code }}
                                @elseif(isset($produit->name) && !empty($produit->name))
                                    {{ $produit->name }}
                                @elseif(isset($produit->nom) && !empty($produit->nom))
                                    {{ $produit->nom }}
                                @elseif(isset($produit->product_name) && !empty($produit->product_name))
                                    {{ $produit->product_name }}
                                @elseif(isset($produit->model) && !empty($produit->model))
                                    {{ $produit->model }}
                                @else
                                    ID:{{ $produit->id }}
                                @endif
                                @if(!$loop->last), @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Instructions de scan -->
        <div class="bg-gray-100 p-1 text-center border-t border-black">
            <p class="text-xs">Scannez le QR code pour les détails</p>
        </div>
    </div>
    
    <!-- Statut QZ Tray et messages -->
    <div class="mt-4 max-w-md mx-auto">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-sm mr-2">Statut QZ Tray:</span>
                <span id="qz-status" class="badge bg-warning text-white text-xs px-2 py-1 rounded">Vérification...</span>
            </div>
            <div>
                <span id="print-success" class="badge bg-success text-white text-xs px-2 py-1 rounded" style="display: none;">
                    Impression envoyée!
                </span>
            </div>
        </div>
        <div id="qz-error" class="mt-2 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm" style="display: none;"></div>
    </div>
    
    <!-- Boutons d'action -->
    <div class="text-center mt-4 space-x-2 max-w-md mx-auto">
        <button id="printButton" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-md shadow text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Imprimer avec QZ Tray
        </button>
        <a href="{{ route('chantiers.show', $chantier->id) }}" class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-1 px-4 rounded-md shadow text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour
        </a>
    </div>
</div>

<style>
    /* Styles pour le QR code */
    .qr-container img {
        width: 100%;
        height: auto;
        max-width: 80px; /* Limite la taille du QR code */
    }
    
    /* Classe pour les éléments à imprimer en rouge */
    .print-red {
        color: #ff0000;
    }
    
    /* Pour s'assurer que le texte noir est bien affiché */
    .text-black {
        color: #000000;
    }
    
    /* Styles pour l'étiquette */
    .label-container {
        max-width: 62mm; /* Largeur du rouleau DK22251 */
    }
    
    /* Styles d'impression */
    @media print {
        body {
            margin: 0;
            padding: 0;
            background-color: white;
        }
        
        .container {
            max-width: 62mm;
            padding: 0;
            margin: 0;
        }
        
        .label-container {
            border: none;
            box-shadow: none;
            width: 62mm;
            max-width: 62mm;
        }
        
        .print-red {
            color: #ff0000 !important; /* Force la couleur rouge pour l'impression */
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        button, a {
            display: none !important;
        }
        
        /* Optimisations pour Brother QL-820NWBc */
        .qr-container {
            border: 1px solid black !important;
            background-color: white !important;
        }
        
        /* S'assurer que tous les textes sont correctement colorés à l'impression */
        .text-black {
            color: #000000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .qr-container img {
            max-width: 60px; /* Légèrement plus petit pour l'impression */
        }
    }

    /* Styles spécifiques pour les étiquettes Brother */
    @page {
        size: 62mm 100mm; /* Taille du rouleau DK22251 */
        margin: 0;
    }
</style>

@endsection

@section('scripts')
<!-- Inclure QZ Tray JavaScript -->
<script src="{{ asset('js/qz-tray.js') }}"></script>

<!-- Script d'impression QZ Tray -->
{!! $qzTrayService->getQzTrayPrintScript($printData) !!}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Bouton d'impression QZ Tray
        const printButton = document.getElementById('printButton');
        
        if (printButton) {
            printButton.addEventListener('click', function() {
                window.QZTray.printFromController(@json($printData));
            });
        }
    });
</script>
@endsection