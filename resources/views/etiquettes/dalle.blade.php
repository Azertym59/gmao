@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Étiquette Dalle</h1>
        <div class="flex space-x-2">
            <button id="print-btn" onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Imprimer l'étiquette
            </button>
            <a href="{{ route('dalles.show', $dalle) }}" class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Étiquette à imprimer -->
    <div class="max-w-2xl mx-auto">
        <div class="etiquette-dalle bg-white border-2 border-gray-300 p-6 rounded-lg shadow-lg print:shadow-none">
            <!-- En-tête -->
            <div class="mb-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">TECALED</h2>
                    <p class="text-sm text-gray-600">Suivi de dalle LED</p>
                </div>
                <div class="text-right">
                    <p class="text-sm">Dalle <span class="font-semibold">#{{ $dalle->id }}</span></p>
                    <p class="text-sm">{{ date('d/m/Y') }}</p>
                </div>
            </div>

            <!-- Informations dalle -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm font-semibold mb-1">Produit</p>
                    <p class="text-sm">{{ $dalle->produit->marque }} {{ $dalle->produit->modele }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold mb-1">Dalle</p>
                    <p class="text-sm">{{ $dalle->largeur }}x{{ $dalle->hauteur }} mm</p>
                </div>
                <div>
                    <p class="text-sm font-semibold mb-1">Modules</p>
                    <p class="text-sm">{{ $dalle->nb_modules }} ({{ $dalle->disposition_modules }})</p>
                </div>
                <div>
                    <p class="text-sm font-semibold mb-1">Pitch</p>
                    <p class="text-sm">{{ $dalle->produit->pitch }} mm</p>
                </div>
            </div>

            <!-- Informations chantier -->
            <div class="mb-6">
                <p class="text-sm font-semibold mb-1">Chantier</p>
                <p class="text-sm">{{ $dalle->produit->chantier->nom }} ({{ $dalle->produit->chantier->reference }})</p>
                <p class="text-sm">{{ $dalle->produit->chantier->client->societe }}</p>
            </div>

            <!-- QR Code -->
            <div class="flex justify-center mb-4">
                <img src="{{ $qrCode }}" alt="QR Code" class="w-48 h-48">
            </div>

            <!-- Instructions -->
            <div class="text-center text-xs text-gray-500">
                <p>Scanner ce code pour accéder aux détails de cette dalle</p>
                <p class="mt-1">{{ route('dalles.show', $dalle->id) }}</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .etiquette-dalle, .etiquette-dalle * {
        visibility: visible;
    }
    .etiquette-dalle {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 1cm;
    }
    @page {
        size: A6;
        margin: 0;
    }
}
</style>
@endsection