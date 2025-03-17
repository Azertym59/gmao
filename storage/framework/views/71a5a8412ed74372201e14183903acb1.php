<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'route',
    'type' => 'default',
    'buttonStyle' => '',
    'tag' => 'button',
    'onclick' => '',
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
    'route',
    'type' => 'default',
    'buttonStyle' => '',
    'tag' => 'button',
    'onclick' => '',
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
    $baseClass = 'btn inline-flex items-center justify-center shadow-md transition-all duration-300 hover:scale-105';
    
    // Classes selon le type
    $typeClass = match($type) {
        'qrcode' => 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white border border-blue-700 hover:from-blue-700 hover:to-indigo-700',
        'brother' => 'text-white border border-fuchsia-700',
        'zebra' => 'bg-gradient-to-r from-gray-800 to-black text-white border border-gray-700 hover:from-gray-700 hover:to-gray-900',
        'default' => 'bg-gradient-to-r from-gray-600 to-gray-800 text-white border border-gray-700 hover:from-gray-500 hover:to-gray-700',
    };
    
    // Style inline pour Brother (couleur spécifique)
    $inlineStyle = $type === 'brother' ? 'background: linear-gradient(135deg, #b01e8e 0%, #d027a6 100%);' : '';
    
    // Taille
    $sizeClass = match($size) {
        'sm' => 'px-3 py-1 text-xs rounded-md',
        'lg' => 'px-5 py-3 text-base rounded-xl',
        default => 'px-4 py-2 text-sm rounded-lg',
    };
    
    $classes = "{$baseClass} {$typeClass} {$sizeClass} {$buttonStyle}";
?>

<?php if($tag === 'a'): ?>
    <a href="<?php echo e($route); ?>" <?php echo e($attributes->merge(['class' => $classes])); ?> style="<?php echo e($inlineStyle); ?>">
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button onclick="<?php echo e($onclick); ?>" <?php echo e($attributes->merge(['class' => $classes])); ?> style="<?php echo e($inlineStyle); ?>">
        <?php echo e($slot); ?>

    </button>
<?php endif; ?><?php /**PATH /var/www/gmao/resources/views/components/print-button.blade.php ENDPATH**/ ?>