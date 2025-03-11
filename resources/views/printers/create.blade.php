@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('printers.index') }}" class="text-blue-500 hover:text-blue-700 mr-2">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Ajouter une imprimante</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <form action="{{ route('printers.store') }}" method="POST">
            @csrf
            
            <!-- Informations de base -->
            <div class="border-b pb-4 mb-4">
                <h2 class="text-xl font-semibold mb-4">Informations générales</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'imprimante <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Modèle d'imprimante <span class="text-red-500">*</span></label>
                        <select name="model" id="model" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                            <option value="">Sélectionnez un modèle</option>
                            @foreach(App\Models\Printer::getSupportedModels() as $value => $label)
                                <option value="{{ $value }}" {{ old('model') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('model')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Configuration de connexion -->
            <div class="border-b pb-4 mb-4">
                <h2 class="text-xl font-semibold mb-4">Configuration de connexion</h2>
                
                <div class="mb-4">
                    <label for="connection_type" class="block text-sm font-medium text-gray-700 mb-1">Type de connexion</label>
                    <select name="connection_type" id="connection_type" class="w-full px-3 py-2 border border-gray-300 rounded-md" onchange="toggleConnectionFields()">
                        @foreach(App\Models\Printer::getConnectionTypes() as $value => $label)
                            <option value="{{ $value }}" {{ old('connection_type', 'network') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div id="network-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="ip_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse IP</label>
                        <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address') }}" placeholder="192.168.1.100" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        @error('ip_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="port" class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                        <input type="number" name="port" id="port" value="{{ old('port', 9100) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        @error('port')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Configuration des étiquettes -->
            <div class="border-b pb-4 mb-4">
                <h2 class="text-xl font-semibold mb-4">Format d'étiquette</h2>
                
                <div class="mb-4">
                    <label for="label_format" class="block text-sm font-medium text-gray-700 mb-1">Format de rouleau</label>
                    <select name="label_format" id="label_format" class="w-full px-3 py-2 border border-gray-300 rounded-md" onchange="updateLabelDimensions()">
                        @foreach(App\Models\Printer::getBrotherRollFormats() as $value => $label)
                            <option value="{{ $value }}" {{ old('label_format', 'dk22251') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="label_width" class="block text-sm font-medium text-gray-700 mb-1">Largeur (mm) <span class="text-red-500">*</span></label>
                        <input type="number" name="label_width" id="label_width" value="{{ old('label_width', 62) }}" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        @error('label_width')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="label_height" class="block text-sm font-medium text-gray-700 mb-1">Hauteur (mm) <span class="text-red-500">*</span></label>
                        <input type="number" name="label_height" id="label_height" value="{{ old('label_height', 100) }}" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        @error('label_height')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="bg-gray-100 p-3 rounded-lg mt-3 text-sm">
                    <p><strong>Note:</strong> Pour les rouleaux continus comme le DK22251, la hauteur correspond à la longueur désirée pour chaque étiquette.</p>
                </div>
            </div>
            
            <!-- Options supplémentaires -->
            <div class="mb-4">
                <h2 class="text-xl font-semibold mb-4">Options supplémentaires</h2>
                
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default') ? 'checked' : '' }} class="rounded">
                    <label for="is_default" class="text-sm font-medium text-gray-700">Définir comme imprimante par défaut</label>
                </div>
                
                <div class="flex items-center space-x-2 mt-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="rounded">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Imprimante active</label>
                </div>
            </div>
            
            <div class="mt-6 text-right">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    Enregistrer l'imprimante
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleConnectionFields() {
        const connectionType = document.getElementById('connection_type').value;
        const networkFields = document.getElementById('network-fields');
        
        if (connectionType === 'network') {
            networkFields.style.display = 'grid';
        } else {
            networkFields.style.display = 'none';
        }
    }
    
    function updateLabelDimensions() {
        const format = document.getElementById('label_format').value;
        const widthInput = document.getElementById('label_width');
        const heightInput = document.getElementById('label_height');
        
        switch(format) {
            case 'dk22251':
                widthInput.value = 62;
                heightInput.value = 100;
                break;
            case 'dk22205':
                widthInput.value = 62;
                heightInput.value = 100;
                break;
            case 'dk11204':
                widthInput.value = 17;
                heightInput.value = 54;
                break;
            case 'dk11203':
                widthInput.value = 17;
                heightInput.value = 87;
                break;
            // Les autres formats peuvent être ajoutés ici
        }
    }
    
    // Exécuter au chargement
    document.addEventListener('DOMContentLoaded', function() {
        toggleConnectionFields();
    });
</script>
@endsection