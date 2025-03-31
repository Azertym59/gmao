<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
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
    </head>
    <body class="font-sans antialiased text-text-primary">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-app-bg">
            <div>
                <a href="/">
                    <img src="<?php echo e(asset('images/logo-repair.png')); ?>" alt="TecaLED Logo" class="w-40">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 glassmorphism overflow-hidden sm:rounded-xl">
                <?php echo e($slot); ?>

            </div>
        </div>
    </body>
</html>
<?php /**PATH /var/www/gmao/resources/views/layouts/guest.blade.php ENDPATH**/ ?>