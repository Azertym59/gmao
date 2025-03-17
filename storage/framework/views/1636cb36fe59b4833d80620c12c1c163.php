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
    
    <!-- QZ Tray -->
    <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.2.0/qz-tray.js"></script>
    <script src="<?php echo e(asset('js/qz-tray.js')); ?>?v=<?php echo e(time()); ?>"></script>
    
    <!-- Stack pour Meta Tags -->
    <?php echo $__env->yieldPushContent('meta'); ?>
    
    <!-- Style force prioritaire pour le menu latéral -->
    <style>
        /* Forcer les éléments du menu à être blancs */
        #sidebar .sidebar-item,
        #sidebar .sidebar-item span,
        #sidebar a,
        #sidebar a span {
            color: white !important;
            text-decoration: none !important;
        }
        
        /* Style hover */
        #sidebar .sidebar-item:hover,
        #sidebar a:hover {
            color: white !important;
            text-decoration: none !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Style actif */
        #sidebar .sidebar-item.active,
        #sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
            font-weight: 500 !important;
        }
        
        /* Titre de section Configuration en vert */
        #sidebar h3.text-xs {
            color: #10B981 !important; /* Vert vif */
            font-weight: 600 !important;
            letter-spacing: 0.05em !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-app-bg text-text-primary">
    <div class="flex h-screen overflow-hidden">
    <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <!-- Main content -->
        <div class="flex-1 flex flex-col ml-64 transition-all duration-300">
            <!-- Top navbar -->
            <header class="glassmorphism sticky top-0 z-10 flex items-center justify-between h-16 px-4 sm:px-6">
                <!-- Mobile menu button -->
                <button id="sidebar-toggle" class="text-gray-500 hover:text-white focus:outline-none focus:text-white lg:hidden">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Breadcrumb -->
                <div class="ml-4 flex-1">
                    <h2 class="text-xl font-semibold">
                        <?php echo e($header ?? ''); ?>

                    </h2>
                </div>
                
                <!-- Right section -->
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false, notifications: [], count: 0 }" @click.away="open = false">
                        <button @click="
                            open = !open; 
                            if (open) {
                                fetch('<?php echo e(route('notifications.unread')); ?>')
                                    .then(res => res.json())
                                    .then(data => {
                                        notifications = data.notifications;
                                        count = data.count;
                                    });
                            }
                        " class="p-1 text-gray-400 rounded-full hover:text-white focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <!-- Badge de notifications non lues -->
                            <span 
                                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full" 
                                x-show="count > 0"
                                x-text="count"
                            ></span>
                        </button>
                        
                        <!-- Dropdown notifications -->
                        <div 
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="glassmorphism absolute right-0 mt-2 w-80 rounded-md shadow-lg z-50"
                        >
                            <div class="py-1 border-b border-gray-700">
                                <div class="flex justify-between items-center px-4 py-2">
                                    <h3 class="text-sm font-medium text-white">Notifications</h3>
                                    <a href="<?php echo e(route('notifications.index')); ?>" class="text-xs text-accent-blue hover:text-blue-400">
                                        Voir toutes
                                    </a>
                                </div>
                            </div>
                            <div class="max-h-60 overflow-y-auto">
                                <div x-show="notifications.length === 0" class="px-4 py-6 text-center text-gray-400">
                                    <p>Pas de nouvelles notifications</p>
                                </div>
                                <template x-for="notification in notifications" :key="notification.id">
                                    <a :href="notification.link" class="block px-4 py-3 hover:bg-gray-800/50 border-b border-gray-700">
                                        <div class="flex justify-between">
                                            <span class="font-medium text-white" x-text="notification.title"></span>
                                            <span class="text-xs text-gray-400" x-text="new Date(notification.created_at).toLocaleTimeString()"></span>
                                        </div>
                                        <p class="text-sm text-gray-400 mt-1" x-text="notification.message"></p>
                                    </a>
                                </template>
                            </div>
                            <div class="py-1">
                                <a href="<?php echo e(route('notifications.mark-all-as-read')); ?>" onclick="event.preventDefault(); document.getElementById('mark-all-form').submit();" class="block px-4 py-2 text-sm text-center text-accent-blue hover:text-blue-400">
                                    Marquer tout comme lu
                                </a>
                                <form id="mark-all-form" action="<?php echo e(route('notifications.mark-all-as-read')); ?>" method="POST" class="hidden">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-y-auto bg-app-bg p-4 sm:p-6">
                <div class="main-content">
                    <!-- Flash Messages -->
                    <?php if(session('success')): ?>
                        <div id="successMessage" class="bg-green-500/80 border border-green-400 text-white px-4 py-3 rounded-lg shadow-lg mb-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span><?php echo e(session('success')); ?></span>
                            </div>
                            <button onclick="document.getElementById('successMessage').style.display = 'none'" class="text-white hover:text-gray-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('error')): ?>
                        <div id="errorMessage" class="bg-red-500/80 border border-red-400 text-white px-4 py-3 rounded-lg shadow-lg mb-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span><?php echo e(session('error')); ?></span>
                            </div>
                            <button onclick="document.getElementById('errorMessage').style.display = 'none'" class="text-white hover:text-gray-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('info')): ?>
                        <div id="infoMessage" class="bg-blue-500/80 border border-blue-400 text-white px-4 py-3 rounded-lg shadow-lg mb-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span><?php echo e(session('info')); ?></span>
                            </div>
                            <button onclick="document.getElementById('infoMessage').style.display = 'none'" class="text-white hover:text-gray-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session('warning')): ?>
                        <div id="warningMessage" class="bg-yellow-500/80 border border-yellow-400 text-white px-4 py-3 rounded-lg shadow-lg mb-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span><?php echo e(session('warning')); ?></span>
                            </div>
                            <button onclick="document.getElementById('warningMessage').style.display = 'none'" class="text-white hover:text-gray-100">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Contenu principal -->
                    <div class="space-y-6">
                    <?php if(isset($slot)): ?>
                        <?php echo e($slot); ?>

                    <?php else: ?>
                        <?php echo $__env->yieldContent('content', ''); ?>
                    <?php endif; ?>
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="glassmorphism py-4 px-6 text-center text-sm text-text-secondary">
                <p>&copy; <?php echo e(date('Y')); ?> TecaLED - GMAO. Tous droits réservés.</p>
            </footer>
        </div>
    </div>
    
    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mainContent = document.querySelector('.flex-1.flex.flex-col');
            
            sidebarToggle.addEventListener('click', function() {
                if (sidebar.classList.contains('-translate-x-full')) {
                    // Open sidebar
                    sidebar.classList.remove('-translate-x-full');
                    mainContent.classList.remove('ml-0');
                    mainContent.classList.add('ml-64');
                } else {
                    // Close sidebar
                    sidebar.classList.add('-translate-x-full');
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-0');
                }
            });
            
            // Handle responsive layout
            function checkSize() {
                if (window.innerWidth < 1024) {
                    sidebar.classList.add('-translate-x-full');
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-0');
                } else {
                    sidebar.classList.remove('-translate-x-full');
                    mainContent.classList.remove('ml-0');
                    mainContent.classList.add('ml-64');
                }
            }
            
            // Check on load
            checkSize();
            
            // Check on resize
            window.addEventListener('resize', checkSize);
            
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
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #121212;
            color: #F3F4F6;
        }
        
        .glassmorphism {
            background: rgba(30, 30, 30, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .sidebar-item {
            transition: all 0.2s ease;
        }
        
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }
        
        .animated-bg {
            background: linear-gradient(45deg, #3B82F6, #8B5CF6, #EC4899);
            background-size: 600% 600%;
            animation: gradientAnimation 12s ease infinite;
        }
        
        @keyframes gradientAnimation {
            0% { background-position: 0% 50% }
            50% { background-position: 100% 50% }
            100% { background-position: 0% 50% }
        }
        
        .tooltip-container {
            position: relative;
        }
        
        .tooltip-container:hover .tooltip {
            opacity: 1;
            visibility: visible;
        }
        
        .tooltip {
            opacity: 0;
            visibility: hidden;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: calc(100% + 5px);
            background: rgba(20, 20, 20, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            transition: all 0.2s ease;
            white-space: nowrap;
            z-index: 10;
        }
        
        .tooltip:after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-top: 5px solid rgba(20, 20, 20, 0.9);
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
        }
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.3;
            }
        }

        
    </style>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /var/www/gmao/resources/views/layouts/app.blade.php ENDPATH**/ ?>