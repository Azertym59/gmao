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
        
        .chart-container {
            height: 280px;
        }
        
        .animate-pulse-slow {
            animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
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
    </style>
</head>
<body class="antialiased min-h-screen bg-app-bg">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="bg-sidebar flex-shrink-0 w-64 fixed h-full z-10 transition-all duration-300">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="animated-bg p-4 flex items-center justify-center h-16">
                    <span class="text-white text-xl font-bold">GMAO TecaLED</span>
                </div>
                
                <!-- Menu principal -->
                <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg {{ request()->routeIs('dashboard') ? 'bg-accent-blue/20 text-accent-blue' : 'hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Tableau de bord</span>
                    </a>
                    
                    <a href="{{ route('chantiers.index') }}" class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg {{ request()->routeIs('chantiers.*') ? 'bg-accent-green/20 text-accent-green' : 'hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Chantiers</span>
                    </a>
                    
                    <a href="{{ route('clients.index') }}" class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg {{ request()->routeIs('clients.*') ? 'bg-accent-purple/20 text-accent-purple' : 'hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Clients</span>
                    </a>
                    
                    <a href="{{ route('produits.index') }}" class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg {{ request()->routeIs('produits.*') ? 'bg-accent-pink/20 text-accent-pink' : 'hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                        <span>Produits</span>
                    </a>
                    
                    <a href="{{ route('interventions.index') }}" class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg {{ request()->routeIs('interventions.*') ? 'bg-accent-yellow/20 text-accent-yellow' : 'hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Interventions</span>
                    </a>
                    
                    <a href="{{ route('rapports.index') }}" class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg {{ request()->routeIs('rapports.*') ? 'bg-accent-red/20 text-accent-red' : 'hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Rapports</span>
                    </a>

                    <div class="pt-4 mt-4 border-t border-gray-700">
                        <h3 class="px-4 text-sm font-semibold text-text-secondary uppercase tracking-wider">
                            Configuration
                        </h3>
                    </div>
                    
                    <a href="{{ route('profile.edit') }}" class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg {{ request()->routeIs('profile.*') ? 'bg-blue-500/20 text-blue-500' : 'hover:bg-gray-800' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Mon profil</span>
                    </a>
                </nav>
                
                <!-- Profile section -->
                <div class="border-t border-gray-700 p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full bg-gray-700" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ Auth::user()->name }}">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-text-primary">{{ Auth::user()->name }}</p>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs text-text-secondary hover:text-text-primary">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <h2 class="text-xl font-semibold text-text-primary">
                        @yield('header')
                    </h2>
                </div>
                
                <!-- Right section -->
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- Notifications -->
                    <button class="p-1 text-gray-400 rounded-full hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 overflow-y-auto bg-app-bg p-4 sm:p-6">
                @if (isset($header))
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endif
                
                <!-- Flash Messages -->
                @if (session('success'))
                    <div id="successMessage" class="bg-green-500/80 border border-green-400 text-white px-4 py-3 rounded-lg shadow-lg mb-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="document.getElementById('successMessage').style.display = 'none'" class="text-white hover:text-gray-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
                
                @if (session('error'))
                    <div id="errorMessage" class="bg-red-500/80 border border-red-400 text-white px-4 py-3 rounded-lg shadow-lg mb-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="document.getElementById('errorMessage').style.display = 'none'" class="text-white hover:text-gray-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif
                
                @if (session('info'))
                    <div id="infoMessage" class="bg-blue-500/80 border border-blue-400 text-white px-4 py-3 rounded-lg shadow-lg mb-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('info') }}</span>
                        </div>
                        <button onclick="document.getElementById('infoMessage').style.display = 'none'" class="text-white hover:text-gray-100">
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
                const messages = document.querySelectorAll('#successMessage, #errorMessage, #infoMessage');
                messages.forEach(function(message) {
                    message.style.display = 'none';
                });
            }, 5000);
        });
    </script>
</body>
</html>