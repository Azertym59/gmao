<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-white leading-tight">
            {{ __('Créer un modèle d\'étiquette') }}
        </h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-white">Nouveau modèle d'étiquette</h3>
                        <a href="{{ route('admin.etiquettes.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-150 ease-in-out flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Retour
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-500/30 border border-red-500/50 text-red-400 rounded-lg">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.etiquettes.store') }}" method="POST" id="templateForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="name" :value="__('Nom du modèle')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" 
                                    :value="old('name')" required autofocus />
                            </div>
                            
                            <div>
                                <x-input-label for="type" :value="__('Type d\'étiquette')" />
                                <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-white focus:border-accent-yellow focus:ring focus:ring-accent-yellow/30 transition-all">
                                    <option value="chantier" {{ old('type') == 'chantier' ? 'selected' : '' }}>Chantier</option>
                                    <option value="dalle" {{ old('type') == 'dalle' ? 'selected' : '' }}>Dalle</option>
                                    <option value="module" {{ old('type') == 'module' ? 'selected' : '' }}>Module</option>
                                </select>
                            </div>
                            
                            <div>
                                <x-input-label for="width" :value="__('Largeur (mm)')" />
                                <x-text-input id="width" name="width" type="number" step="0.1" class="mt-1 block w-full" 
                                    :value="old('width', 62)" required />
                            </div>
                            
                            <div>
                                <x-input-label for="height" :value="__('Hauteur (mm)')" />
                                <x-text-input id="height" name="height" type="number" step="0.1" class="mt-1 block w-full" 
                                    :value="old('height', 100)" required />
                            </div>
                            
                            <div>
                                <x-input-label for="size_type" :value="__('Type de taille')" />
                                <select id="size_type" name="size_type" class="mt-1 block w-full rounded-md border-gray-700 bg-gray-800 text-white focus:border-accent-yellow focus:ring focus:ring-accent-yellow/30 transition-all">
                                    <option value="fixed" {{ old('size_type') == 'fixed' ? 'selected' : '' }}>Taille fixe</option>
                                    <option value="continuous" {{ old('size_type') == 'continuous' ? 'selected' : '' }}>Rouleau continu</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center mt-6">
                                <input id="is_default" name="is_default" type="checkbox" class="rounded bg-gray-800 border-gray-700 text-accent-yellow focus:ring-accent-yellow/30 transition-all" 
                                    {{ old('is_default') ? 'checked' : '' }} />
                                <label for="is_default" class="ml-2 text-gray-300">Définir comme modèle par défaut pour ce type</label>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <x-input-label for="template_editor" :value="__('Conception de l\'étiquette')" class="mb-3" />
                            
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <div class="lg:col-span-2">
                                    <div id="templateEditor" class="border border-gray-600 bg-gray-900 rounded-lg p-4 min-h-[400px] relative">
                                        <!-- Template editor canvas -->
                                        <div id="editorCanvas" class="relative bg-white mx-auto shadow-xl overflow-hidden" style="width: 248px; height: 400px; transform: scale(1);">
                                            <!-- Elements will be added here via JavaScript -->
                                        </div>
                                        
                                        <div class="absolute bottom-3 right-3 flex gap-2">
                                            <button type="button" id="zoomIn" class="p-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                            <button type="button" id="zoomOut" class="p-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="lg:col-span-1">
                                    <div class="border border-gray-600 bg-gray-900 rounded-lg p-4 min-h-[400px]">
                                        <h4 class="text-white font-medium mb-3">Éléments disponibles</h4>
                                        
                                        <div class="space-y-3">
                                            <div class="border border-gray-700 rounded p-3 bg-gray-800 cursor-pointer element-button" draggable="true" data-element-type="text">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                                                    </svg>
                                                    <span>Texte</span>
                                                </div>
                                            </div>
                                            
                                            <div class="border border-gray-700 rounded p-3 bg-gray-800 cursor-pointer element-button" draggable="true" data-element-type="variable">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-accent-yellow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                    </svg>
                                                    <span>Variable</span>
                                                </div>
                                            </div>
                                            <div class="border border-accent-yellow rounded p-3 bg-gray-800 text-center cursor-pointer" id="open-variable-selector">
                                                <span class="text-accent-yellow font-medium">Sélectionner une variable</span>
                                            </div>
                                            
                                            <div class="border border-gray-700 rounded p-3 bg-gray-800 cursor-pointer element-button" draggable="true" data-element-type="image">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span>Image</span>
                                                </div>
                                            </div>
                                            
                                            <div class="border border-gray-700 rounded p-3 bg-gray-800 cursor-pointer element-button" draggable="true" data-element-type="qrcode">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                    </svg>
                                                    <span>QR Code</span>
                                                </div>
                                            </div>
                                            
                                            <div class="border border-gray-700 rounded p-3 bg-gray-800 cursor-pointer element-button" draggable="true" data-element-type="rectangle">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                                                    </svg>
                                                    <span>Rectangle</span>
                                                </div>
                                            </div>
                                            
                                            <div class="border border-gray-700 rounded p-3 bg-gray-800 cursor-pointer element-button" draggable="true" data-element-type="line">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16" />
                                                    </svg>
                                                    <span>Ligne</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-6">
                                            <h4 class="text-white font-medium mb-3">Propriétés</h4>
                                            <div id="elementProperties" class="text-gray-400 text-sm">
                                                <p>Sélectionnez un élément pour modifier ses propriétés</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden field to store the template data as JSON -->
                        <input type="hidden" name="template_data" id="templateData" value="{{ old('template_data', '[]') }}">
                        
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.etiquettes.index') }}" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition duration-150 ease-in-out">
                                Annuler
                            </a>
                            <button type="submit" class="px-4 py-2 bg-accent-yellow text-white rounded-lg hover:bg-yellow-500 transition duration-150 ease-in-out">
                                Enregistrer
                            </button>
                        </div>
                        
                        <!-- Variable Selector Modal -->
                        <div id="variable-selector-modal" class="fixed inset-0 flex items-center justify-center hidden z-50">
                            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
                            <div class="relative bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
                                <button type="button" class="absolute top-3 right-3 text-gray-400 hover:text-white" id="close-variable-modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <div id="variable-selector-content">
                                    <!-- Content will be loaded here via AJAX -->
                                    <div class="text-center py-8">
                                        <svg class="animate-spin h-8 w-8 text-accent-yellow mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="mt-3 text-gray-400">Chargement des variables...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Template Editor JavaScript
            const canvas = document.getElementById('editorCanvas');
            const templateData = document.getElementById('templateData');
            const typeSelect = document.getElementById('type');
            const widthInput = document.getElementById('width');
            const heightInput = document.getElementById('height');
            const sizeTypeSelect = document.getElementById('size_type');
            const zoomInBtn = document.getElementById('zoomIn');
            const zoomOutBtn = document.getElementById('zoomOut');
            
            let currentScale = 1;
            let selectedElement = null;
            let elements = [];
            let draggedElement = null;
            
            // Helper function to create variable placeholders safely
            function variablePlaceholder(name) {
                return '{' + '{' + name + '}' + '}';
            }
            
            // Try to load existing template data if editing
            try {
                const savedData = templateData.value;
                if (savedData && savedData !== '[]') {
                    elements = JSON.parse(savedData);
                    renderElements();
                }
            } catch (e) {
                console.error('Error loading template data', e);
            }
            
            // Update canvas dimensions when width/height/type changes
            function updateCanvasDimensions() {
                const width = parseFloat(widthInput.value);
                const height = parseFloat(heightInput.value);
                
                // Convert mm to px (1mm ≈ 4px for display)
                const scaleFactor = 4;
                canvas.style.width = width * scaleFactor + 'px';
                canvas.style.height = height * scaleFactor + 'px';
                
                // Update canvas background based on label type
                if (typeSelect.value === 'chantier') {
                    canvas.style.backgroundColor = '#f0f9ff'; // Light blue background
                } else if (typeSelect.value === 'dalle') {
                    canvas.style.backgroundColor = '#f0fdf4'; // Light green background
                } else if (typeSelect.value === 'module') {
                    canvas.style.backgroundColor = '#fffbeb'; // Light yellow background
                }
            }
            
            // Initialize canvas dimensions
            updateCanvasDimensions();
            
            // Event listeners for dimension changes
            widthInput.addEventListener('input', updateCanvasDimensions);
            heightInput.addEventListener('input', updateCanvasDimensions);
            typeSelect.addEventListener('change', updateCanvasDimensions);
            
            // Zoom functionality
            zoomInBtn.addEventListener('click', function() {
                if (currentScale < 2) {
                    currentScale += 0.1;
                    canvas.style.transform = 'scale(' + currentScale + ')';
                }
            });
            
            zoomOutBtn.addEventListener('click', function() {
                if (currentScale > 0.5) {
                    currentScale -= 0.1;
                    canvas.style.transform = 'scale(' + currentScale + ')';
                }
            });
            
            // Create function to generate an element based on type
            function createNewElement(elementType) {
                const newElement = {
                    type: elementType,
                    id: 'element_' + Date.now(),
                    x: 10,
                    y: 10,
                    width: 100,
                    height: 30,
                    content: elementType === 'text' ? 'Texte' : 
                             elementType === 'variable' ? variablePlaceholder('nom') : '',
                    fontSize: 14,
                    fontWeight: 'normal',
                    textAlign: 'left',
                    color: '#000000',
                    backgroundColor: elementType === 'rectangle' ? '#ffffff' : 'transparent',
                    borderColor: elementType === 'rectangle' ? '#000000' : 'transparent',
                    borderWidth: elementType === 'rectangle' ? 1 : 0,
                    borderRadius: 0
                };
                
                if (elementType === 'qrcode') {
                    newElement.width = 80;
                    newElement.height = 80;
                    newElement.content = variablePlaceholder('qrcode_url');
                }
                
                if (elementType === 'image') {
                    newElement.width = 80;
                    newElement.height = 40;
                    newElement.src = '/images/logo-repair.png';
                }
                
                if (elementType === 'line') {
                    newElement.height = 2;
                    newElement.width = 100;
                    newElement.color = '#000000';
                }
                
                return newElement;
            }
            
            // Add element on click
            const elementButtons = document.querySelectorAll('.element-button');
            elementButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const elementType = this.getAttribute('data-element-type');
                    const newElement = createNewElement(elementType);
                    
                    // Add new element
                    elements.push(newElement);
                    
                    // Update and render
                    updateTemplateData();
                    renderElements();
                    
                    // Select the new element
                    selectElement(newElement.id);
                });
            });
            
            // Drag and drop from element palette
            const draggableElements = document.querySelectorAll('[data-element-type]');
            draggableElements.forEach(el => {
                el.addEventListener('dragstart', function(e) {
                    draggedElement = createNewElement(this.getAttribute('data-element-type'));
                });
            });
            
            // Canvas drop area
            canvas.addEventListener('dragover', function(e) {
                e.preventDefault();
            });
            
            canvas.addEventListener('drop', function(e) {
                e.preventDefault();
                if (draggedElement) {
                    // Adjust position based on drop location
                    const rect = canvas.getBoundingClientRect();
                    draggedElement.x = (e.clientX - rect.left) / currentScale;
                    draggedElement.y = (e.clientY - rect.top) / currentScale;
                    
                    // Add new element
                    elements.push(draggedElement);
                    draggedElement = null;
                    
                    // Update and render
                    updateTemplateData();
                    renderElements();
                }
            });
            
            // Render all elements on the canvas
            function renderElements() {
                // Clear canvas
                while (canvas.firstChild) {
                    canvas.removeChild(canvas.firstChild);
                }
                
                // Render each element
                elements.forEach(element => {
                    const el = document.createElement('div');
                    el.id = element.id;
                    el.classList.add('template-element');
                    el.style.position = 'absolute';
                    el.style.left = element.x + 'px';
                    el.style.top = element.y + 'px';
                    el.style.width = element.width + 'px';
                    el.style.height = element.height + 'px';
                    el.style.cursor = 'move';
                    el.style.userSelect = 'none';
                    
                    // Style based on element type
                    if (element.type === 'text' || element.type === 'variable') {
                        el.style.fontSize = element.fontSize + 'px';
                        el.style.fontWeight = element.fontWeight;
                        el.style.color = element.color;
                        el.style.textAlign = element.textAlign;
                        el.innerText = element.content;
                        
                        if (element.type === 'variable') {
                            el.style.backgroundColor = 'rgba(252, 211, 77, 0.2)';
                            el.style.border = '1px dashed #f59e0b';
                            el.style.padding = '2px 4px';
                            el.style.borderRadius = '3px';
                        }
                    } else if (element.type === 'qrcode') {
                        el.style.backgroundColor = '#eeeeee';
                        el.style.border = '1px solid #cccccc';
                        el.style.display = 'flex';
                        el.style.justifyContent = 'center';
                        el.style.alignItems = 'center';
                        el.style.fontSize = '10px';
                        el.innerText = 'QR Code';
                    } else if (element.type === 'image') {
                        // Create image element
                        const img = document.createElement('img');
                        img.src = element.src;
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'contain';
                        el.appendChild(img);
                    } else if (element.type === 'rectangle') {
                        el.style.backgroundColor = element.backgroundColor;
                        el.style.border = element.borderWidth + 'px solid ' + element.borderColor;
                        el.style.borderRadius = element.borderRadius + 'px';
                    } else if (element.type === 'line') {
                        el.style.backgroundColor = element.color;
                        el.style.height = element.height + 'px';
                    }
                    
                    // Make elements draggable within canvas and selectable
                    el.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        
                        // First, select the element
                        selectElement(element.id);
                        
                        // Setup for drag operation
                        const startX = e.clientX;
                        const startY = e.clientY;
                        const startLeft = parseInt(el.style.left);
                        const startTop = parseInt(el.style.top);
                        
                        // Track if this is a click or a drag
                        let hasMoved = false;
                        
                        function moveElement(e) {
                            hasMoved = true;
                            const newLeft = startLeft + (e.clientX - startX) / currentScale;
                            const newTop = startTop + (e.clientY - startY) / currentScale;
                            
                            el.style.left = newLeft + 'px';
                            el.style.top = newTop + 'px';
                            
                            // Update element position
                            const idx = elements.findIndex(item => item.id === element.id);
                            if (idx !== -1) {
                                elements[idx].x = newLeft;
                                elements[idx].y = newTop;
                                updateTemplateData();
                            }
                        }
                        
                        function stopMoving(e) {
                            document.removeEventListener('mousemove', moveElement);
                            document.removeEventListener('mouseup', stopMoving);
                            
                            // If it was just a click (no movement), ensure the element is selected
                            if (!hasMoved) {
                                selectElement(element.id);
                            }
                        }
                        
                        document.addEventListener('mousemove', moveElement);
                        document.addEventListener('mouseup', stopMoving);
                    });
                    
                    canvas.appendChild(el);
                });
            }
            
            // Select an element to edit its properties
            function selectElement(id) {
                const prevSelected = document.querySelector('.template-element.selected');
                if (prevSelected) {
                    prevSelected.classList.remove('selected');
                    prevSelected.style.outline = 'none';
                }
                
                selectedElement = elements.find(el => el.id === id);
                
                if (selectedElement) {
                    const el = document.getElementById(id);
                    el.classList.add('selected');
                    el.style.outline = '2px solid #3b82f6';
                    
                    renderElementProperties();
                }
            }
            
            // Render properties panel for the selected element
            function renderElementProperties() {
                const propertiesPanel = document.getElementById('elementProperties');
                
                if (!selectedElement) {
                    propertiesPanel.innerHTML = '<p>Sélectionnez un élément pour modifier ses propriétés</p>';
                    return;
                }
                
                let html = '<div class="space-y-3">';
                
                // Common properties
                html += 
                    '<div>' +
                        '<label class="block text-xs mb-1">Position X</label>' +
                        '<input type="number" class="property-input" data-property="x" value="' + selectedElement.x + '" ' + 
                               'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                    '</div>' +
                    '<div>' +
                        '<label class="block text-xs mb-1">Position Y</label>' +
                        '<input type="number" class="property-input" data-property="y" value="' + selectedElement.y + '"' +
                               'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                    '</div>' +
                    '<div>' +
                        '<label class="block text-xs mb-1">Largeur</label>' +
                        '<input type="number" class="property-input" data-property="width" value="' + selectedElement.width + '"' +
                               'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                    '</div>' +
                    '<div>' +
                        '<label class="block text-xs mb-1">Hauteur</label>' +
                        '<input type="number" class="property-input" data-property="height" value="' + selectedElement.height + '"' +
                               'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                    '</div>';
                
                // Type-specific properties
                if (selectedElement.type === 'text' || selectedElement.type === 'variable') {
                    html += 
                        '<div>' +
                            '<label class="block text-xs mb-1">Contenu</label>' +
                            '<input type="text" class="property-input" data-property="content" value="' + selectedElement.content + '"' +
                                   'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                        '</div>' +
                        '<div>' +
                            '<label class="block text-xs mb-1">Taille de police</label>' +
                            '<input type="number" class="property-input" data-property="fontSize" value="' + selectedElement.fontSize + '"' +
                                   'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                        '</div>' +
                        '<div>' +
                            '<label class="block text-xs mb-1">Couleur</label>' +
                            '<input type="color" class="property-input" data-property="color" value="' + selectedElement.color + '"' +
                                   'style="width: 100%; background: #374151; border: 1px solid #4B5563; padding: 4px 8px; rounded-md">' +
                        '</div>' +
                        '<div>' +
                            '<label class="block text-xs mb-1">Style</label>' +
                            '<select class="property-input" data-property="fontWeight"' +
                                    'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                                '<option value="normal" ' + (selectedElement.fontWeight === 'normal' ? 'selected' : '') + '>Normal</option>' +
                                '<option value="bold" ' + (selectedElement.fontWeight === 'bold' ? 'selected' : '') + '>Gras</option>' +
                            '</select>' +
                        '</div>' +
                        '<div>' +
                            '<label class="block text-xs mb-1">Alignement</label>' +
                            '<select class="property-input" data-property="textAlign"' +
                                    'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                                '<option value="left" ' + (selectedElement.textAlign === 'left' ? 'selected' : '') + '>Gauche</option>' +
                                '<option value="center" ' + (selectedElement.textAlign === 'center' ? 'selected' : '') + '>Centre</option>' +
                                '<option value="right" ' + (selectedElement.textAlign === 'right' ? 'selected' : '') + '>Droite</option>' +
                            '</select>' +
                        '</div>';
                } else if (selectedElement.type === 'rectangle') {
                    html += 
                        '<div>' +
                            '<label class="block text-xs mb-1">Couleur de fond</label>' +
                            '<input type="color" class="property-input" data-property="backgroundColor" value="' + selectedElement.backgroundColor + '"' +
                                   'style="width: 100%; background: #374151; border: 1px solid #4B5563; padding: 4px 8px; rounded-md">' +
                        '</div>' +
                        '<div>' +
                            '<label class="block text-xs mb-1">Couleur de bordure</label>' +
                            '<input type="color" class="property-input" data-property="borderColor" value="' + selectedElement.borderColor + '"' +
                                   'style="width: 100%; background: #374151; border: 1px solid #4B5563; padding: 4px 8px; rounded-md">' +
                        '</div>' +
                        '<div>' +
                            '<label class="block text-xs mb-1">Épaisseur de bordure</label>' +
                            '<input type="number" class="property-input" data-property="borderWidth" value="' + selectedElement.borderWidth + '"' +
                                   'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                        '</div>' +
                        '<div>' +
                            '<label class="block text-xs mb-1">Rayon de bordure</label>' +
                            '<input type="number" class="property-input" data-property="borderRadius" value="' + selectedElement.borderRadius + '"' +
                                   'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                        '</div>';
                } else if (selectedElement.type === 'line') {
                    html += 
                        '<div>' +
                            '<label class="block text-xs mb-1">Couleur</label>' +
                            '<input type="color" class="property-input" data-property="color" value="' + selectedElement.color + '"' +
                                   'style="width: 100%; background: #374151; border: 1px solid #4B5563; padding: 4px 8px; rounded-md">' +
                        '</div>' +
                        '<div>' +
                            '<label class="block text-xs mb-1">Épaisseur</label>' +
                            '<input type="number" class="property-input" data-property="height" value="' + selectedElement.height + '"' +
                                   'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                        '</div>';
                } else if (selectedElement.type === 'image') {
                    html += 
                        '<div>' +
                            '<label class="block text-xs mb-1">URL de l\'image</label>' +
                            '<input type="text" class="property-input" data-property="src" value="' + selectedElement.src + '"' +
                                   'style="width: 100%; background: #374151; border: 1px solid #4B5563; color: white; padding: 4px 8px; rounded-md">' +
                        '</div>';
                }
                
                // Delete button
                html += 
                    '<div class="mt-4">' +
                        '<button type="button" id="deleteElement" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">' +
                            'Supprimer l\'élément' +
                        '</button>' +
                    '</div>';
                
                html += '</div>';
                
                propertiesPanel.innerHTML = html;
                
                // Add property change listeners
                document.querySelectorAll('.property-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const property = this.getAttribute('data-property');
                        let value = this.value;
                        
                        // Convert number inputs to numbers
                        if (this.type === 'number') {
                            value = parseFloat(value);
                        }
                        
                        // Update element property
                        const idx = elements.findIndex(el => el.id === selectedElement.id);
                        if (idx !== -1) {
                            elements[idx][property] = value;
                            selectedElement = elements[idx];
                            updateTemplateData();
                            renderElements();
                            selectElement(selectedElement.id);
                        }
                    });
                });
                
                // Add delete handler
                document.getElementById('deleteElement').addEventListener('click', function() {
                    if (selectedElement) {
                        const idx = elements.findIndex(el => el.id === selectedElement.id);
                        if (idx !== -1) {
                            elements.splice(idx, 1);
                            selectedElement = null;
                            updateTemplateData();
                            renderElements();
                            renderElementProperties();
                        }
                    }
                });
            }
            
            // Update the hidden input with current template data
            function updateTemplateData() {
                templateData.value = JSON.stringify(elements);
            }
            
            // Initialize the editor
            renderElements();
            renderElementProperties();
            
            // Handle form submission
            document.getElementById('templateForm').addEventListener('submit', function(e) {
                updateTemplateData();
            });
            
            // Variable Selector Modal
            const variableSelectorModal = document.getElementById('variable-selector-modal');
            const variableSelectorContent = document.getElementById('variable-selector-content');
            const openVariableSelectorBtn = document.getElementById('open-variable-selector');
            const closeVariableModalBtn = document.getElementById('close-variable-modal');
            
            // Open variable selector
            openVariableSelectorBtn.addEventListener('click', function() {
                // Show modal
                variableSelectorModal.classList.remove('hidden');
                
                // Load variable selector content based on template type
                fetch('/api/etiquettes/variables/' + typeSelect.value)
                    .then(response => response.json())
                    .then(data => {
                        // Render the variable selector with the variables data
                        const modalContent = 
                            '<div class="variable-selector-container">' +
                                '<h4 class="font-medium text-white mb-3">Sélectionner une variable</h4>' +
                                
                                '<div class="mb-3">' +
                                    '<label for="variable-search" class="sr-only">Recherche de variables</label>' +
                                    '<div class="relative">' +
                                        '<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">' +
                                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">' +
                                                '<path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />' +
                                            '</svg>' +
                                        '</div>' +
                                        '<input id="variable-search" type="text" placeholder="Rechercher une variable..." ' +
                                            'class="bg-gray-700 text-white focus:ring-accent-yellow focus:border-accent-yellow block w-full pl-10 pr-3 py-2 border-gray-600 rounded-md">' +
                                    '</div>' +
                                '</div>' +
                                
                                '<div class="variable-categories space-y-4">' +
                                    '<!-- Common variables -->' +
                                    '<div class="variable-category">' +
                                        '<h5 class="font-medium text-gray-300 mb-2">Variables communes</h5>' +
                                        '<div class="bg-gray-800 rounded-md p-2 space-y-1">' +
                                            Object.entries(data.common).map(function([key, variable]) {
                                                return '<div class="variable-item p-2 hover:bg-gray-700 rounded cursor-pointer" ' +
                                                    'data-variable="' + key + '" data-description="' + variable.description + '" data-example="' + variable.example + '">' +
                                                    '<div class="flex items-center">' +
                                                        '<span class="text-accent-yellow">' + variable.label + '</span>' +
                                                        '<span class="ml-auto text-xs text-gray-400">{{' + key + '}}</span>' +
                                                    '</div>' +
                                                '</div>';
                                            }).join('') +
                                        '</div>' +
                                    '</div>' +
                                    
                                    '<!-- Type-specific variables -->' +
                                    '<div class="variable-category">' +
                                        '<h5 class="font-medium text-gray-300 mb-2">Variables spécifiques</h5>' +
                                        '<div class="bg-gray-800 rounded-md p-2 space-y-1">' +
                                            Object.entries(data.specific).map(function([key, variable]) {
                                                return '<div class="variable-item p-2 hover:bg-gray-700 rounded cursor-pointer" ' +
                                                    'data-variable="' + key + '" data-description="' + variable.description + '" data-example="' + variable.example + '">' +
                                                    '<div class="flex items-center">' +
                                                        '<span class="text-accent-yellow">' + variable.label + '</span>' +
                                                        '<span class="ml-auto text-xs text-gray-400">{{' + key + '}}</span>' +
                                                    '</div>' +
                                                '</div>';
                                            }).join('') +
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                                
                                '<div class="mt-4 p-3 bg-gray-900 rounded-md">' +
                                    '<h5 class="font-medium text-gray-400 mb-1">Description</h5>' +
                                    '<p id="variable-description" class="text-sm text-gray-300">Sélectionnez une variable pour voir sa description</p>' +
                                    
                                    '<h5 class="font-medium text-gray-400 mt-3 mb-1">Exemple</h5>' +
                                    '<p id="variable-example" class="text-sm text-gray-300">Sélectionnez une variable pour voir un exemple</p>' +
                                '</div>' +
                                
                                '<div class="mt-4 flex justify-between">' +
                                    '<button type="button" id="cancel-variable" class="px-3 py-1.5 bg-gray-700 text-white rounded hover:bg-gray-600">' +
                                        'Annuler' +
                                    '</button>' +
                                    '<button type="button" id="insert-variable" class="px-3 py-1.5 bg-accent-yellow text-white rounded hover:bg-yellow-500" disabled>' +
                                        'Insérer' +
                                    '</button>' +
                                '</div>' +
                            '</div>';
                        
                        
                        variableSelectorContent.innerHTML = modalContent;
                        
                        // Add event listeners to variable items
                        document.querySelectorAll('.variable-item').forEach(item => {
                            item.addEventListener('click', function() {
                                // Remove previous selection
                                const previousSelected = document.querySelector('.variable-item.selected');
                                if (previousSelected) {
                                    previousSelected.classList.remove('selected', 'bg-accent-yellow/20', 'border-accent-yellow');
                                }
                                
                                // Set new selection
                                this.classList.add('selected', 'bg-accent-yellow/20', 'border', 'border-accent-yellow');
                                
                                // Update description and example
                                document.getElementById('variable-description').textContent = this.getAttribute('data-description');
                                document.getElementById('variable-example').textContent = this.getAttribute('data-example');
                                
                                // Enable insert button
                                document.getElementById('insert-variable').disabled = false;
                            });
                        });
                        
                        // Add search functionality
                        document.getElementById('variable-search').addEventListener('input', function() {
                            const searchTerm = this.value.toLowerCase();
                            
                            document.querySelectorAll('.variable-item').forEach(item => {
                                const variableName = item.getAttribute('data-variable').toLowerCase();
                                const variableLabel = item.querySelector('span').textContent.toLowerCase();
                                
                                if (variableName.includes(searchTerm) || variableLabel.includes(searchTerm)) {
                                    item.style.display = 'block';
                                } else {
                                    item.style.display = 'none';
                                }
                            });
                        });
                        
                        // Add cancel button handler
                        document.getElementById('cancel-variable').addEventListener('click', function() {
                            variableSelectorModal.classList.add('hidden');
                        });
                        
                        // Add insert button handler
                        document.getElementById('insert-variable').addEventListener('click', function() {
                            const selectedItem = document.querySelector('.variable-item.selected');
                            if (selectedItem) {
                                const variableName = selectedItem.getAttribute('data-variable');
                                
                                // Create new variable element
                                const newVariable = {
                                    type: 'variable',
                                    id: 'element_' + Date.now(),
                                    x: 10,
                                    y: 10,
                                    width: 100,
                                    height: 30,
                                    content: variablePlaceholder(variableName),
                                    fontSize: 14,
                                    fontWeight: 'normal',
                                    textAlign: 'left',
                                    color: '#000000'
                                };
                                
                                // Add to elements array and render
                                elements.push(newVariable);
                                updateTemplateData();
                                renderElements();
                                
                                // Close modal
                                variableSelectorModal.classList.add('hidden');
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error loading variables:', error);
                        variableSelectorContent.innerHTML = 
                            '<div class="text-center py-8">' +
                                '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-red-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">' +
                                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />' +
                                '</svg>' +
                                '<p class="mt-3 text-gray-400">Erreur lors du chargement des variables</p>' +
                            '</div>';
                    });
            });
            
            // Close variable selector modal when clicking on close button
            closeVariableModalBtn.addEventListener('click', function() {
                variableSelectorModal.classList.add('hidden');
            });
            
            // Close variable selector modal when clicking outside
            variableSelectorModal.addEventListener('click', function(e) {
                if (e.target === variableSelectorModal) {
                    variableSelectorModal.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>