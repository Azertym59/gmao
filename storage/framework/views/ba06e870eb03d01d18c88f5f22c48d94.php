<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label' => 'Marque',
    'placeholder' => 'Recherchez ou saisissez une marque...',
    'required' => false
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'label' => 'Marque',
    'placeholder' => 'Recherchez ou saisissez une marque...',
    'required' => false
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div x-data="marqueAutocomplete()" <?php echo e($attributes->merge(['class' => 'mb-4'])); ?>>
    <label class="block text-sm font-medium text-gray-300 mb-1"><?php echo e($label); ?></label>
    <div class="relative">
        <input 
            type="text" 
            x-model="query"
            id="marque-search"
            @blur="onBlur()"
            placeholder="<?php echo e($placeholder); ?>" 
            class="block w-full rounded-md bg-gray-700 border-gray-600 text-white uppercase focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
            <?php echo e($required ? 'required' : ''); ?>

        >
        
        <!-- Indicateur de chargement -->
        <div x-show="isLoading" class="absolute right-3 top-3">
            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
    
    <!-- Résultats de la recherche -->
    <div x-show="showResults" class="absolute mt-1 w-full max-w-lg bg-gray-800 border border-gray-700 rounded-md shadow-lg z-50 max-h-60 overflow-y-auto">
        <ul class="py-1">
            <template x-for="marque in results" :key="marque.id">
                <li @click="selectMarque(marque)" class="px-4 py-2 hover:bg-gray-700 cursor-pointer">
                    <div x-text="marque.text" class="font-medium text-white"></div>
                </li>
            </template>
        </ul>
    </div>
</div><?php /**PATH /var/www/gmao/resources/views/components/marque-autocomplete.blade.php ENDPATH**/ ?>