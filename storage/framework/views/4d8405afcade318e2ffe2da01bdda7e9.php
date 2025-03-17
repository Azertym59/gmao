<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'tag' => 'button', 
    'href' => '#', 
    'type' => 'primary',
    'tooltip' => '',
    'tooltipPosition' => 'bottom',
    'size' => 'md'
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
    'tag' => 'button', 
    'href' => '#', 
    'type' => 'primary',
    'tooltip' => '',
    'tooltipPosition' => 'bottom',
    'size' => 'md'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $baseClass = 'btn-icon';
    
    // Définir la classe de taille
    $sizeClass = match($size) {
        'xs' => 'btn-icon-xs',
        'sm' => 'btn-icon-sm',
        'lg' => 'btn-icon-lg',
        'xl' => 'btn-icon-xl',
        default => 'btn-icon-md'
    };
    
    // Définir la classe de type
    $typeClass = match($type) {
        'secondary' => 'btn-secondary',
        'success' => 'btn-success',
        'danger' => 'btn-danger',
        'info' => 'btn-info',
        'outline' => 'btn-outline',
        'action' => 'btn-action',
        default => 'btn-primary'
    };
    
    // Classes pour la position du tooltip
    $tooltipClass = $tooltip ? 'tooltip tooltip-' . $tooltipPosition : '';
    
    // Classes combinées
    $classes = "{$baseClass} {$sizeClass} {$typeClass} {$tooltipClass}";
?>

<?php if($tag === 'a'): ?>
<a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => $classes])); ?> <?php if($tooltip): ?> data-tooltip="<?php echo e($tooltip); ?>" <?php endif; ?>>
    <?php echo e($slot); ?>

</a>
<?php else: ?>
<button <?php echo e($attributes->merge(['class' => $classes])); ?> <?php if($tooltip): ?> data-tooltip="<?php echo e($tooltip); ?>" <?php endif; ?>>
    <?php echo e($slot); ?>

</button>
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/components/icon-button.blade.php ENDPATH**/ ?>