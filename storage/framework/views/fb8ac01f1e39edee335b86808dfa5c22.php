<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'status',
    'type' => '',
    'customLabel' => ''
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
    'status',
    'type' => '',
    'customLabel' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    // Si le type n'est pas fourni, le déduire du statut
    $statusType = $type ?: match($status) {
        'termine', 'completed', 'success', 'success', 'good', 'ok', 'valid' => 'success',
        'en_cours', 'started', 'in_progress', 'pending', 'warning' => 'warning',
        'defaillant', 'error', 'danger', 'critical', 'failed', 'broken' => 'danger',
        default => 'info'
    };
    
    // Définir la classe CSS en fonction du type
    $cssClass = match($statusType) {
        'success' => 'badge badge-success',
        'warning' => 'badge badge-warning',
        'danger' => 'badge badge-danger',
        default => 'badge badge-info'
    };
    
    // Générer un label lisible si aucun n'est fourni
    $label = $customLabel ?: match($status) {
        'non_commence' => 'Non commencé',
        'en_cours' => 'En cours',
        'termine' => 'Terminé',
        'defaillant' => 'Défaillant',
        default => ucfirst(str_replace('_', ' ', $status))
    };
?>

<span <?php echo e($attributes->merge(['class' => $cssClass])); ?>>
    <?php echo e($label); ?>

</span><?php /**PATH /var/www/gmao/resources/views/components/status-badge.blade.php ENDPATH**/ ?>