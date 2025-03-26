@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">Étiquette Module</h1>
        <div class="flex space-x-2">
            <button id="print-btn" onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Imprimer l'étiquette
            </button>
            <a href="{{ route('modules.show', $module) }}" class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg shadow flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Étiquette à imprimer -->
    <div class="max-w-md mx-auto">
        <div class="etiquette-module bg-white border-2 border-gray-300 p-4 rounded-lg shadow-lg print:shadow-none">
            <!-- En-tête -->
            <div class="mb-2 text-center">
                <h2 class="text-lg font-bold">TECALED</h2>
                <p class="text-xs text-gray-600">Module LED #{{ $module->id }}</p>
            </div>

            <!-- QR Code -->
            <div class="flex justify-center mb-3">
                <img src="{{ $qrCode }}" alt="QR Code" class="w-36 h-36">
            </div>

            <!-- Informations module -->
            <div class="text-center text-sm mb-2">
                <p>{{ $module->dalle->produit->marque }} {{ $module->dalle->produit->modele }}</p>
                <p>Dalle #{{ $module->dalle->id }} - Position {{ $module->position_x }}x{{ $module->position_y }}</p>
                <p>{{ $module->resolution_x }}x{{ $module->resolution_y }}</p>
            </div>

            <!-- État -->
            <div class="flex justify-center mb-2">
                <span class="inline-block px-2 py-1 text-xs rounded-full 
                    @if($module->etat == 'termine') bg-green-100 text-green-800
                    @elseif($module->etat == 'en_cours') bg-yellow-100 text-yellow-800
                    @elseif($module->etat == 'defaillant') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($module->etat) }}
                </span>
            </div>

            <!-- Instructions -->
            <div class="text-center text-xs text-gray-500">
                <p>Scanner ce code pour accéder aux détails</p>
                <p class="mt-1">{{ route('modules.show', $module->id) }}</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .etiquette-module, .etiquette-module * {
        visibility: visible;
    }
    .etiquette-module {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0.5cm;
    }
    @page {
        size: 80mm 120mm;
        margin: 0;
    }
}
</style>
@endsection