@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Ajouter une imprimante</h1>
        <a href="{{ route('printers.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
            Retour
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('printers.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom de l'imprimante -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'imprimante</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" 
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type d'imprimante -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type d'imprimante</label>
                    <select name="type" id="type" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="standard" {{ old('type') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="thermal" {{ old('type') == 'thermal' ? 'selected' : '' }}>Thermique</option>
                        <option value="label" {{ old('type') == 'label' ? 'selected' : '' }}>Étiquettes</option>
                        <option value="brother_label" {{ old('type') == 'brother_label' ? 'selected' : '' }}>Brother (étiquettes)</option>
                        <option value="qztray" {{ old('type') == 'qztray' ? 'selected' : '' }}>QZ Tray</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Modèle -->
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Modèle</label>
                    <input type="text" name="model" id="model" value="{{ old('model', 'QL-820NWB') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Connection Type -->
                <div>
                    <label for="connection_type" class="block text-sm font-medium text-gray-700 mb-1">Type de connexion</label>
                    <select name="connection_type" id="connection_type" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="network" {{ old('connection_type') == 'network' ? 'selected' : '' }}>Réseau</option>
                        <option value="usb" {{ old('connection_type') == 'usb' ? 'selected' : '' }}>USB</option>
                        <option value="bluetooth" {{ old('connection_type') == 'bluetooth' ? 'selected' : '' }}>Bluetooth</option>
                        <option value="qztray" {{ old('connection_type') == 'qztray' ? 'selected' : '' }}>QZ Tray</option>
                    </select>
                </div>

                <!-- IP Address (for network printers) -->
                <div class="network-fields">
                    <label for="ip_address" class="block text-sm font-medium text-gray-700 mb-1">Adresse IP</label>
                    <input type="text" name="ip_address" id="ip_address" value="{{ old('ip_address') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Port (for network printers) -->
                <div class="network-fields">
                    <label for="port" class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                    <input type="text" name="port" id="port" value="{{ old('port', '9100') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- DPI -->
                <div>
                    <label for="dpi" class="block text-sm font-medium text-gray-700 mb-1">Résolution (DPI)</label>
                    <input type="number" name="dpi" id="dpi" value="{{ old('dpi', '300') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Label Width (for label printers) -->
                <div class="label-fields">
                    <label for="label_width" class="block text-sm font-medium text-gray-700 mb-1">Largeur d'étiquette (mm)</label>
                    <input type="number" name="label_width" id="label_width" value="{{ old('label_width', '62') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Label Height (for label printers) -->
                <div class="label-fields">
                    <label for="label_height" class="block text-sm font-medium text-gray-700 mb-1">Hauteur d'étiquette (mm)</label>
                    <input type="number" name="label_height" id="label_height" value="{{ old('label_height', '29') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Brother Roll Type -->
                <div class="brother-fields">
                    <label for="brother_roll" class="block text-sm font-medium text-gray-700 mb-1">Type de rouleau Brother</label>
                    <select name="brother_roll" id="brother_roll" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="dk22205" {{ old('brother_roll') == 'dk22205' ? 'selected' : '' }}>DK-22205 (Ruban continu 62mm)</option>
                        <option value="dk22210" {{ old('brother_roll') == 'dk22210' ? 'selected' : '' }}>DK-22210 (Ruban continu 29mm)</option>
                        <option value="dk22214" {{ old('brother_roll') == 'dk22214' ? 'selected' : '' }}>DK-22214 (Ruban continu 12mm)</option>
                        <option value="dk11201" {{ old('brother_roll') == 'dk11201' ? 'selected' : '' }}>DK-11201 (Étiquettes adresse 29x90mm)</option>
                        <option value="dk11202" {{ old('brother_roll') == 'dk11202' ? 'selected' : '' }}>DK-11202 (Étiquettes expédition 62x100mm)</option>
                    </select>
                </div>

                <!-- Default Printer -->
                <div class="col-span-2">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_default" class="font-medium text-gray-700">Définir comme imprimante par défaut</label>
                            <p class="text-gray-500">Cette imprimante sera utilisée automatiquement pour toutes les impressions</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-5 border-t border-gray-200">
                <div class="flex justify-end">
                    <a href="{{ route('printers.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annuler
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const connectionTypeSelect = document.getElementById('connection_type');
        const networkFields = document.querySelectorAll('.network-fields');
        const labelFields = document.querySelectorAll('.label-fields');
        const brotherFields = document.querySelectorAll('.brother-fields');
        
        // Function to toggle fields visibility based on printer type
        function toggleFields() {
            const printerType = typeSelect.value;
            const connectionType = connectionTypeSelect.value;
            
            // Handle network fields
            networkFields.forEach(field => {
                field.style.display = connectionType === 'network' ? 'block' : 'none';
            });
            
            // Handle label fields
            labelFields.forEach(field => {
                field.style.display = (printerType === 'label' || printerType === 'thermal' || printerType === 'brother_label') ? 'block' : 'none';
            });
            
            // Handle Brother specific fields
            brotherFields.forEach(field => {
                field.style.display = (printerType === 'brother_label') ? 'block' : 'none';
            });
        }
        
        // Initial toggle
        toggleFields();
        
        // Add event listeners
        typeSelect.addEventListener('change', toggleFields);
        connectionTypeSelect.addEventListener('change', toggleFields);
    });
</script>
@endpush
@endsection