<div class="variable-selector-container">
    <h4 class="font-medium text-white mb-3">Sélectionner une variable</h4>
    
    <div class="mb-3">
        <label for="variable-search" class="sr-only">Recherche de variables</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input id="variable-search" type="text" placeholder="Rechercher une variable..." 
                class="bg-gray-700 text-white focus:ring-accent-yellow focus:border-accent-yellow block w-full pl-10 pr-3 py-2 border-gray-600 rounded-md">
        </div>
    </div>
    
    <div class="variable-categories space-y-4">
        <!-- Common variables -->
        <div class="variable-category">
            <h5 class="font-medium text-gray-300 mb-2">Variables communes</h5>
            <div class="bg-gray-800 rounded-md p-2 space-y-1">
                @foreach($variables['common'] as $key => $var)
                <div class="variable-item p-2 hover:bg-gray-700 rounded cursor-pointer" 
                     data-variable="{{ $key }}" data-description="{{ $var['description'] }}" data-example="{{ $var['example'] }}">
                    <div class="flex items-center">
                        <span class="text-accent-yellow">{{ $var['label'] }}</span>
                        <span class="ml-auto text-xs text-gray-400">{{ '{{' . $key . '}}' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Type-specific variables -->
        <div class="variable-category">
            <h5 class="font-medium text-gray-300 mb-2">Variables spécifiques</h5>
            <div class="bg-gray-800 rounded-md p-2 space-y-1">
                @foreach($variables['specific'] as $key => $var)
                <div class="variable-item p-2 hover:bg-gray-700 rounded cursor-pointer" 
                     data-variable="{{ $key }}" data-description="{{ $var['description'] }}" data-example="{{ $var['example'] }}">
                    <div class="flex items-center">
                        <span class="text-accent-yellow">{{ $var['label'] }}</span>
                        <span class="ml-auto text-xs text-gray-400">{{ '{{' . $key . '}}' }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <div class="mt-4 p-3 bg-gray-900 rounded-md">
        <h5 class="font-medium text-gray-400 mb-1">Description</h5>
        <p id="variable-description" class="text-sm text-gray-300">Sélectionnez une variable pour voir sa description</p>
        
        <h5 class="font-medium text-gray-400 mt-3 mb-1">Exemple</h5>
        <p id="variable-example" class="text-sm text-gray-300">Sélectionnez une variable pour voir un exemple</p>
    </div>
    
    <div class="mt-4 flex justify-between">
        <button type="button" id="cancel-variable" class="px-3 py-1.5 bg-gray-700 text-white rounded hover:bg-gray-600">
            Annuler
        </button>
        <button type="button" id="insert-variable" class="px-3 py-1.5 bg-accent-yellow text-white rounded hover:bg-yellow-500">
            Insérer
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const variableItems = document.querySelectorAll('.variable-item');
        const searchInput = document.getElementById('variable-search');
        const descriptionElement = document.getElementById('variable-description');
        const exampleElement = document.getElementById('variable-example');
        const insertButton = document.getElementById('insert-variable');
        const cancelButton = document.getElementById('cancel-variable');
        
        let selectedVariable = null;
        
        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            variableItems.forEach(item => {
                const variableName = item.getAttribute('data-variable').toLowerCase();
                const variableLabel = item.querySelector('span').textContent.toLowerCase();
                
                if (variableName.includes(searchTerm) || variableLabel.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Variable selection
        variableItems.forEach(item => {
            item.addEventListener('click', function() {
                // Remove previous selection
                const previousSelected = document.querySelector('.variable-item.selected');
                if (previousSelected) {
                    previousSelected.classList.remove('selected', 'bg-accent-yellow/20', 'border-accent-yellow');
                }
                
                // Set new selection
                this.classList.add('selected', 'bg-accent-yellow/20', 'border-accent-yellow');
                selectedVariable = this.getAttribute('data-variable');
                
                // Update description and example
                descriptionElement.textContent = this.getAttribute('data-description');
                exampleElement.textContent = this.getAttribute('data-example');
            });
        });
        
        // Insert variable button
        insertButton.addEventListener('click', function() {
            if (selectedVariable) {
                // Emit custom event with selected variable
                const event = new CustomEvent('variable-selected', {
                    detail: {
                        variable: selectedVariable
                    }
                });
                document.dispatchEvent(event);
                
                // Close the modal (handled by parent)
            }
        });
        
        // Cancel button
        cancelButton.addEventListener('click', function() {
            // Close the modal (handled by parent)
            const event = new CustomEvent('variable-selection-cancelled');
            document.dispatchEvent(event);
        });
    });
</script>