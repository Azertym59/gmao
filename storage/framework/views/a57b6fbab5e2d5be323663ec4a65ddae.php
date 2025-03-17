<a href="<?php echo e($route ?? url()->previous()); ?>" <?php echo e($attributes->merge(['class' => 'btn-action flex items-center'])); ?>>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
    <?php echo e($slot ?? __('Retour')); ?>

</a><?php /**PATH /var/www/gmao/resources/views/components/back-button.blade.php ENDPATH**/ ?>