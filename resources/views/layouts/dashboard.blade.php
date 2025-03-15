<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GMAO TecaLED') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <style>
        /* Effet d'arrière-plan animé */
        .animated-bg {
            background: linear-gradient(-45deg, #121826, #1E293B, #0F172A);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
        }
        
        @keyframes gradientAnimation {
            0% { background-position: 0% 50% }
            50% { background-position: 100% 50% }
            100% { background-position: 0% 50% }
        }
        
        /* Effet de survol pour le menu latéral */
        .sidebar-item {
            transition: all 0.2s ease;
            position: relative;
        }
        
        .sidebar-item:hover {
            transform: translateX(5px);
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            opacity: 0;
            transition: all 0.2s ease;
        }
        
        .sidebar-item:hover::before {
            width: 4px;
            opacity: 1;
        }
        
        /* Effet de glassmorphism amélioré avec animation de bordure */
        .glassmorphism-glow {
            position: relative;
            overflow: hidden;
        }
        
        .glassmorphism-glow::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            width: calc(100% + 4px);
            height: calc(100% + 4px);
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            z-index: -1;
            animation: borderGlow 2s linear infinite;
        }
        
        @keyframes borderGlow {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        /* Effet de focus pour les champs de formulaire */
        .form-control:focus {
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }
        
        /* Tooltip amélioré */
        .tooltip-container {
            position: relative;
        }
        
        .tooltip-container:hover .tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-5px);
        }
        
        .tooltip {
            opacity: 0;
            visibility: hidden;
            position: absolute;
            left: 50%;
            transform: translateX(-50%) translateY(0px);
            bottom: calc(100% + 5px);
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(59, 130, 246, 0.3);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
            z-index: 50;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .tooltip:after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-top: 5px solid rgba(15, 23, 42, 0.95);
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
        }
    </style>
</head>
<body class="antialiased min-h-screen animated-bg">
    <div class="flex h-screen overflow-hidden">
        <!-- Main Navigation (from navigation.blade.php) -->
        @include('layouts.navigation')
        
        <!-- Main content -->
        <div class="flex-1 flex flex-col ml-64 transition-all duration-300">
            <!-- Top navbar -->
            <header class="glassmorphism sticky top-0 z-30 flex items-center justify-between h-16 px-4 sm:px-6">
                <!-- Mobile menu button -->
                <button id="sidebar-toggle" class="text-gray-400 hover:text-white focus:outline-none focus:text-white lg:hidden">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                
                <!-- Breadcrumb -->
                <div class="ml-4 flex-1">
                    <h2 class="text-xl font-semibold text-text-primary">
                        @yield('header')
                    </h2>
                </div>
                
                <!-- Right section -->
                <div class="ml-4 flex items-center md:ml-6 space-x-4">
                    <!-- Notifications -->
                    <a href="{{ route('notifications.index') }}" class="btn-icon text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500 relative">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        {{-- Indicateur de notifications non lues --}}
                        @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-app-bg"></span>
                        @endif
                    </a>
                    
                    <!-- Profil dropdown - Version simplifiée -->
                    <a href="{{ route('profile.edit') }}" class="tooltip-container">
                        <img class="h-8 w-8 rounded-full object-cover border border-blue-500/30" 
                             src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=172554' }}" 
                             alt="{{ auth()->user()->name }}">
                        <span class="tooltip">Modifier mon profil</span>
                    </a>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                @if (isset($header))
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endif
                
                <!-- Flash Messages -->
                @if (session('success'))
                    <div id="successMessage" class="alert alert-success">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="document.getElementById('successMessage').style.display = 'none'" class="text-green-400 hover:text-green-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
                
                @if (session('error'))
                    <div id="errorMessage" class="alert alert-error">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="document.getElementById('errorMessage').style.display = 'none'" class="text-red-400 hover:text-red-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
                
                @if (session('info'))
                    <div id="infoMessage" class="alert alert-info">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('info') }}</span>
                        </div>
                        <button onclick="document.getElementById('infoMessage').style.display = 'none'" class="text-blue-400 hover:text-blue-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
                
                @if (session('warning'))
                    <div id="warningMessage" class="alert alert-warning">
                        <div class="flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>{{ session('warning') }}</span>
                        </div>
                        <button onclick="document.getElementById('warningMessage').style.display = 'none'" class="text-amber-400 hover:text-amber-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
                
                <!-- Contenu principal -->
                <div class="space-y-6">
                    {{ $slot }}
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="glassmorphism py-4 px-6 text-center text-sm text-text-secondary">
                <p>&copy; {{ date('Y') }} TecaLED - GMAO. Tous droits réservés.</p>
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
                    message.style.display = 'none';
                });
            }, 5000);
        });
    </script>
</body>
</html>