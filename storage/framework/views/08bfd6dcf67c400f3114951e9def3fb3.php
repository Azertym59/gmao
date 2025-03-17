<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'chantier',
    'type',
    'buttonType' => 'info',
    'size' => 'md',
    'tooltip' => '',
    'tooltipPosition' => 'top',
    'effectClass' => ''
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
    'chantier',
    'type',
    'buttonType' => 'info',
    'size' => 'md',
    'tooltip' => '',
    'tooltipPosition' => 'top',
    'effectClass' => ''
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $tooltipText = match($type) {
        'created' => $tooltip ?: 'Email création',
        'started' => $tooltip ?: 'Email interventions',
        'completed' => $tooltip ?: 'Email finalisation',
        default => $tooltip ?: 'Envoyer email'
    };
    
    // Définir la classe d'effet en fonction du type d'email si non spécifiée
    $effect = $effectClass ?: match($type) {
        'created' => 'btn-glow',
        'started' => 'pulse-primary',
        'completed' => 'btn-rainbow',
        default => ''
    };
    
    $formId = "email-{$type}-form-" . uniqid();
?>

<span class="inline-block">
    <form id="<?php echo e($formId); ?>" action="<?php echo e(route('emails.chantier', $chantier)); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="email_type" value="<?php echo e($type); ?>">
    </form>
    
    <?php if (isset($component)) { $__componentOriginal658398a0e73a18931bb7def04d911f42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal658398a0e73a18931bb7def04d911f42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon-button','data' => ['tag' => 'button','type' => ''.e($buttonType).'','tooltip' => ''.e($tooltipText).'','tooltipPosition' => ''.e($tooltipPosition).'','size' => ''.e($size).'','class' => ''.e($effect).'','attributes' => $attributes,'onclick' => 'document.getElementById(\''.e($formId).'\').submit();']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tag' => 'button','type' => ''.e($buttonType).'','tooltip' => ''.e($tooltipText).'','tooltipPosition' => ''.e($tooltipPosition).'','size' => ''.e($size).'','class' => ''.e($effect).'','attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes),'onclick' => 'document.getElementById(\''.e($formId).'\').submit();']); ?>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $attributes = $__attributesOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__attributesOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $component = $__componentOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__componentOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
</span>
<?php /**PATH /var/www/gmao/resources/views/components/email-button.blade.php ENDPATH**/ ?>