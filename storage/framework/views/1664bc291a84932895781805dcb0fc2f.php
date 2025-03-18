<a href="<?php echo e($route); ?>" <?php echo e($attributes->merge(['class' => 'btn-action btn-secondary flex items-center'])); ?>>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
    </svg>
    <?php echo e($slot ?? __('Modifier')); ?>

</a><?php /**PATH /var/www/gmao/resources/views/components/edit-button.blade.php ENDPATH**/ ?>