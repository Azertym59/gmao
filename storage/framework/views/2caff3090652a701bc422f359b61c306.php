<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'state',
    'route',
    'model',
    'currentState'
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
    'state',
    'route',
    'model',
    'currentState'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $isActive = $currentState === $state;
    
    $baseClasses = "text-xs px-2 py-1 rounded transition-all duration-150";
    
    $stateClasses = match($state) {
        'non_commence' => "bg-gray-600 hover:bg-gray-500 text-white",
        'en_cours' => "bg-yellow-600 hover:bg-yellow-500 text-white",
        'termine' => "bg-green-600 hover:bg-green-500 text-white",
        'defaillant' => "bg-red-600 hover:bg-red-500 text-white",
        default => "bg-blue-600 hover:bg-blue-500 text-white"
    };
    
    $label = match($state) {
        'non_commence' => 'Non commencé',
        'en_cours' => 'En cours',
        'termine' => 'Terminé',
        'defaillant' => 'Défaillant',
        default => ucfirst(str_replace('_', ' ', $state))
    };
?>

<?php if(!$isActive): ?>
    <form action="<?php echo e($route); ?>" method="POST" class="inline">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PATCH'); ?>
        <input type="hidden" name="etat" value="<?php echo e($state); ?>">
        <button type="submit" <?php echo e($attributes->merge(['class' => "{$baseClasses} {$stateClasses}"])); ?>>
            <?php echo e($label); ?>

        </button>
    </form>
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/components/state-button.blade.php ENDPATH**/ ?>