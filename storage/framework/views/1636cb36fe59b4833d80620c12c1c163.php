<?php
use Illuminate\Support\Facades\Auth;
?>
<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'GMAO TecaLED')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/fixes.css')); ?>">
    
    <!-- QZ Tray -->
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.0/qz-tray.js"></script>
    <script src="<?php echo e(asset('js/qz-tray.js')); ?>?v=<?php echo e(time()); ?>"></script>
    
    <?php echo $__env->yieldPushContent('meta'); ?>
</head>
<body class="font-sans antialiased h-full bg-app-bg text-text-primary">
    <div class="min-h-screen">
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <div class="min-h-screen md:ml-64">
            <!-- Header -->
            <header class="bg-sidebar border-b border-border-light shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <?php if(isset($header)): ?>
                        <?php echo e($header); ?>

                    <?php else: ?>
                        <h1 class="text-2xl font-semibold text-white">
                            <?php echo $__env->yieldContent('header', 'Dashboard'); ?>
                        </h1>
                    <?php endif; ?>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-4">
                <!-- Flash Messages -->
                <?php if(session('success')): ?>
                <div id="successMessage" class="mb-6 alert-success">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p><?php echo e(session('success')); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div id="errorMessage" class="mb-6 alert-error">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-error" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p><?php echo e(session('error')); ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(isset($slot)): ?>
                    <?php echo e($slot); ?>

                <?php else: ?>
                    <?php echo $__env->yieldContent('content'); ?>
                <?php endif; ?>
            </main>
        </div>
    </div>
    
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }
            
            // Auto-hide flash messages after 5 seconds
            setTimeout(function() {
                const messages = document.querySelectorAll('#successMessage, #errorMessage, #infoMessage, #warningMessage');
                messages.forEach(function(message) {
                    if (message) {
                        message.style.display = 'none';
                    }
                });
            }, 5000);
        });
    </script>
</body>
</html><?php /**PATH /var/www/gmao/resources/views/layouts/app.blade.php ENDPATH**/ ?>