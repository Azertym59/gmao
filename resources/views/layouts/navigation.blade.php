<!-- Sidebar / Menu latéral -->
<div id="sidebar" class="bg-sidebar flex-shrink-0 w-64 fixed h-full z-20 transition-all duration-300 shadow-xl">
    <div class="flex flex-col h-full">
        <!-- Logo avec taille maximale -->
        <div class="flex items-center justify-center h-[100px] bg-black bg-opacity-50 p-0 border-b border-border-dark">
            <img src="https://www.tecaled.fr/Logos/Logo%20rectangle%20V2.png" 
                alt="TecaLED" 
                class="w-full max-h-[90px] object-contain px-2">
        </div>
        
        <!-- Menu principal -->
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('dashboard') ? 'bg-accent-blue/20 text-accent-blue font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('dashboard') ? 'box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Tableau de bord</span>
            </a>
            
            <!-- Chantiers -->
            <a href="{{ route('chantiers.index') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('chantiers.*') ? 'bg-accent-green/20 text-accent-green font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('chantiers.*') ? 'box-shadow: 0 0 15px rgba(16, 185, 129, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Chantiers</span>
            </a>
            
            <!-- Clients -->
            <a href="{{ route('clients.index') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('clients.*') ? 'bg-accent-purple/20 text-accent-purple font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('clients.*') ? 'box-shadow: 0 0 15px rgba(139, 92, 246, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Clients</span>
            </a>
            
            <!-- Produits -->
            <a href="{{ route('produits.index') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('produits.*') ? 'bg-accent-pink/20 text-accent-pink font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('produits.*') ? 'box-shadow: 0 0 15px rgba(236, 72, 153, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
                <span>Produits</span>
            </a>
            
            <!-- Catalogue -->
            <a href="{{ route('produits-catalogue.index') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('produits-catalogue.*') ? 'bg-accent-pink/20 text-accent-pink font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('produits-catalogue.*') ? 'box-shadow: 0 0 15px rgba(236, 72, 153, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Catalogue</span>
            </a>
            
            <!-- Interventions -->
            <a href="{{ route('interventions.index') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('interventions.*') ? 'bg-accent-yellow/20 text-accent-yellow font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('interventions.*') ? 'box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Interventions</span>
            </a>
            
            <!-- Rapports -->
            <a href="{{ route('rapports.index') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('rapports.*') ? 'bg-accent-red/20 text-accent-red font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('rapports.*') ? 'box-shadow: 0 0 15px rgba(239, 68, 68, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Rapports</span>
            </a>

            <!-- Section Configuration -->
            <div class="pt-4 mt-6 border-t border-gray-700">
                <h3 class="px-4 text-xs font-semibold text-text-secondary uppercase tracking-wider mb-3">
                    Configuration
                </h3>
            </div>

            <!-- Tableau de bord technicien (si role est technicien) -->
            @if(auth()->check() && auth()->user()->role === 'technicien')
            <a href="{{ route('technicien.dashboard') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('technicien.dashboard') ? 'bg-accent-yellow/20 text-accent-yellow font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('technicien.dashboard') ? 'box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>Mon tableau de bord</span>
            </a>
            @endif

            <!-- Section admin (si role est admin) -->
            @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/20 text-indigo-400 font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('admin.dashboard') ? 'box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Tableau de bord admin</span>
            </a>
            
            <a href="{{ route('admin.users') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('admin.users*') ? 'bg-indigo-500/20 text-indigo-400 font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('admin.users*') ? 'box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Gestion des utilisateurs</span>
            </a>
            
            <!-- Configuration Imprimante (admin seulement) -->
            <a href="{{ route('printers.index') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('printers.*') ? 'bg-accent-blue/20 text-accent-blue font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('printers.*') ? 'box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Configuration Imprimante</span>
            </a>
            @endif
            
            <!-- Mon profil (visible par tous) -->
            <a href="{{ route('profile.edit') }}" 
               class="sidebar-item flex items-center px-4 py-3 text-text-primary rounded-lg transition-all duration-200
               {{ request()->routeIs('profile.*') ? 'bg-accent-blue/20 text-accent-blue font-medium' : 'hover:bg-gray-800/50' }}"
               style="{{ request()->routeIs('profile.*') ? 'box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Mon profil</span>
            </a>
        </nav>
        
        <!-- Profile section du bas -->
        <div class="mt-auto border-t border-gray-700 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full border border-accent-blue/20 shadow-md object-cover" 
                         src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=172554' }}" 
                         alt="{{ Auth::user()->name }}">
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-text-primary">{{ Auth::user()->name }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-text-secondary hover:text-text-primary transition">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>