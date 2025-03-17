<form action="<?php echo e(route('emails.chantier', $chantier)); ?>" method="POST" class="inline">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="email_type" value="<?php echo e($type); ?>">
    <button type="submit" <?php echo e($attributes->merge(['class' => 'btn-info'])); ?>>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <span><?php echo e($slot); ?></span>
    </button>
</form>
<?php /**PATH /var/www/gmao/resources/views/components/email-button.blade.php ENDPATH**/ ?>