@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Dalle: {{ $dalle->reference }}</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <h2 class="text-lg font-semibold mb-2">Informations</h2>
                <p><strong>Produit:</strong> {{ $dalle->produit->reference }}</p>
                <p><strong>Chantier:</strong> {{ $dalle->produit->chantier->name }}</p>
                <p><strong>Client:</strong> {{ $dalle->produit->chantier->client->name }}</p>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold mb-2">Caractéristiques techniques</h2>
                <p><strong>Carte Réception:</strong> {{ $dalle->carte_reception ?? 'N/A' }}</p>
                <p><strong>Hub:</strong> {{ $dalle->hub ?? 'N/A' }}</p>
            </div>
        </div>
        
        <h2 class="text-lg font-semibold mb-2">Modules actuellement installés</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border">Référence</th>
                        <th class="py-2 px-4 border">Dernière intervention</th>
                        <th class="py-2 px-4 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dalle->modules as $module)
                    <tr>
                        <td class="py-2 px-4 border">{{ $module->reference }}</td>
                        <td class="py-2 px-4 border">
                            {{ $module->interventions->sortByDesc('date')->first() ? $module->interventions->sortByDesc('date')->first()->date->format('d/m/Y') : 'Aucune' }}
                        </td>
                        <td class="py-2 px-4 border">
                            <a href="{{ route('qrcode.module.show', $module->id) }}" class="text-blue-500 hover:underline">Voir détails</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-2 px-4 border text-center">Aucun module installé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection