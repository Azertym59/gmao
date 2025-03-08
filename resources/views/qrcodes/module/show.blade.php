@extends('layouts.app')

@section('content')
<div class="container mx-auto py-4">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Module: {{ $module->reference }}</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <h2 class="text-lg font-semibold mb-2">Informations</h2>
                <p><strong>Dalle actuelle:</strong> {{ $module->dalle->reference }}</p>
                <p><strong>Produit:</strong> {{ $module->dalle->produit->reference }}</p>
                <p><strong>Chantier:</strong> {{ $module->dalle->produit->chantier->name }}</p>
            </div>
        </div>
        
        <h2 class="text-lg font-semibold mb-2">Historique des interventions</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border">Date</th>
                        <th class="py-2 px-4 border">Technicien</th>
                        <th class="py-2 px-4 border">Diagnostic</th>
                        <th class="py-2 px-4 border">Réparation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($module->interventions->sortByDesc('date') as $intervention)
                    <tr>
                        <td class="py-2 px-4 border">{{ $intervention->date->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 border">{{ $intervention->user->name }}</td>
                        <td class="py-2 px-4 border">
                            @foreach($intervention->diagnostics as $diagnostic)
                                <div class="mb-1">{{ $diagnostic->details }}</div>
                            @endforeach
                        </td>
                        <td class="py-2 px-4 border">
                            @foreach($intervention->reparations as $reparation)
                                <div class="mb-1">{{ $reparation->details }}</div>
                            @endforeach
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-2 px-4 border text-center">Aucune intervention</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection