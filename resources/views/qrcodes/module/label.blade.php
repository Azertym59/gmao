@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="bg-white p-6 rounded-lg shadow-lg border-2 border-gray-300 max-w-lg mx-auto">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold">Module: {{ $module->reference }}</h1>
            <p class="text-gray-600">Dalle: {{ $module->dalle->reference }}</p>
            <p class="text-gray-600">Chantier: {{ $module->dalle->produit->chantier->name }}</p>
        </div>
        
        <div class="flex justify-center mb-4">
            {!! $qrCode !!}
        </div>
        
        <div class="text-center text-xs text-gray-500">
            <p>Scannez ce QR code pour voir les détails du module</p>
        </div>
    </div>
    
    <div class="text-center mt-6">
        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Imprimer l'étiquette
        </button>
        <a href="{{ route('modules.show', $module->id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
            Retour
        </a>
    </div>
</div>
@endsection