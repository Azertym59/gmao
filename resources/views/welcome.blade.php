<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GMAO TecaLED - Gestion de Maintenance Assistée par Ordinateur</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #ffffff;
                color: #181a1e;
            }
            .hero-section {
                background: linear-gradient(135deg, #ffffff, #f8f9fa);
                position: relative;
                overflow: hidden;
                padding: 120px 0 100px;
                text-align: center;
            }
            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 6px;
                background: linear-gradient(90deg, #00d624, #0076d1, #00b2ff);
            }
            .hero-pattern {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: radial-gradient(#00d624 0.5px, transparent 0.5px), radial-gradient(#0076d1 0.5px, transparent 0.5px);
                background-size: 30px 30px;
                background-position: 0 0, 15px 15px;
                opacity: 0.05;
            }
            .features-section {
                padding: 100px 0;
                background-color: #f8f9fa;
            }
            .feature-card {
                padding: 32px;
                border-radius: 12px;
                background-color: #ffffff;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
                height: 100%;
                transition: all 0.3s ease;
                border: 1px solid rgba(24, 26, 30, 0.05);
            }
            .feature-card:hover {
                transform: translateY(-6px);
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                border-color: rgba(0, 118, 209, 0.2);
            }
            .footer {
                background-color: #181a1e;
                color: #ffffff;
                padding: 40px 0;
            }
            .icon-circle {
                background-color: rgba(0, 118, 209, 0.1);
                width: 64px;
                height: 64px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 20px;
            }
            .btn-primary {
                background-color: #00d624;
                color: white;
                transition: all 0.3s ease;
            }
            .btn-primary:hover {
                background-color: #00bf20;
                box-shadow: 0 5px 15px rgba(0, 214, 36, 0.3);
            }
            .btn-secondary {
                background-color: #0076d1;
                color: white;
                transition: all 0.3s ease;
            }
            .btn-secondary:hover {
                background-color: #0065b3;
                box-shadow: 0 5px 15px rgba(0, 118, 209, 0.3);
            }
            .btn-outline {
                background-color: transparent;
                color: #0076d1;
                border: 2px solid #0076d1;
                transition: all 0.3s ease;
            }
            .btn-outline:hover {
                background-color: #0076d1;
                color: white;
            }
            .stats-section {
                position: relative;
                background-color: #ffffff;
                padding: 80px 0;
            }
            .stat-card {
                text-align: center;
                padding: 24px;
                border-radius: 12px;
                background-color: white;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
            }
            .stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            }
            .cta-section {
                background: linear-gradient(135deg, #181a1e, #2a2d37);
                color: white;
                padding: 80px 0;
                position: relative;
                overflow: hidden;
            }
            .cta-glow {
                position: absolute;
                width: 200px;
                height: 200px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(0, 214, 36, 0.3) 0%, rgba(0, 118, 209, 0.1) 50%, rgba(0, 0, 0, 0) 70%);
            }
            .cta-glow-1 {
                top: -50px;
                left: 10%;
            }
            .cta-glow-2 {
                bottom: -100px;
                right: 20%;
            }
        </style>
    </head>
    <body>
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-pattern"></div>
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-4xl mx-auto">
                    <img src="{{ asset('images/Logo rectangle V2.png') }}" alt="TecaLED Logo" class="h-24 md:h-32 mx-auto mb-8">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 text-text-primary">
                        <span class="bg-gradient-to-r from-accent-primary via-accent-secondary to-accent-tertiary bg-clip-text text-transparent">
                            GMAO TecaLED
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-text-secondary">Gestion de Maintenance Assistée par Ordinateur</p>
                    @if (auth()->check() && auth()->user()->client_id)
                    <p class="text-xl md:text-2xl mb-8 font-medium text-accent-secondary">Bienvenue {{ auth()->user()->client->civilite }} {{ auth()->user()->client->getNomCompletSansDoublonAttribute() }}</p>
                    @endif
                    <div class="flex flex-wrap gap-4 justify-center mt-8">
                        @if (\Illuminate\Support\Facades\Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn-primary px-8 py-4 rounded-lg font-semibold text-base md:text-lg">Accéder au tableau de bord</a>
                            @else
                                <a href="{{ route('login') }}" class="btn-secondary px-8 py-4 rounded-lg font-semibold text-base md:text-lg">Connexion</a>
                                @if (\Illuminate\Support\Facades\Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-outline px-8 py-4 rounded-lg font-semibold text-base md:text-lg">Inscription</a>
                                @endif
                            @endauth
                        @endif
                        <a href="{{ route('client.login') }}" class="btn-primary px-8 py-4 rounded-lg font-semibold text-base md:text-lg">Espace Client</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="features-section">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h5 class="text-accent-secondary font-semibold mb-2 uppercase tracking-wider">Fonctionnalités</h5>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Tout ce dont vous avez besoin pour gérer votre maintenance</h2>
                    <p class="text-lg text-text-secondary max-w-2xl mx-auto">Une solution complète pour optimiser vos opérations de maintenance</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="feature-card">
                        <div class="icon-circle mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#0076d1" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-center">Gestion des interventions</h3>
                        <p class="text-text-secondary text-center">Planifiez, suivez et documentez toutes vos interventions de maintenance pour optimiser vos processus.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="icon-circle mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00d624" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-center">Gestion des stocks</h3>
                        <p class="text-text-secondary text-center">Suivez vos pièces détachées, gérez les mouvements de stock et évitez les ruptures.</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="icon-circle mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#00b2ff" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-center">Rapports détaillés</h3>
                        <p class="text-text-secondary text-center">Générez des rapports personnalisés pour analyser les performances et prendre des décisions éclairées.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h5 class="text-accent-secondary font-semibold mb-2 uppercase tracking-wider">Performance</h5>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4">Optimisez votre maintenance</h2>
                    <p class="text-lg text-text-secondary max-w-2xl mx-auto">Notre solution vous aide à obtenir des résultats concrets</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="stat-card">
                        <div class="mb-2">
                            <span class="text-accent-primary text-4xl font-bold">25%</span>
                        </div>
                        <p class="text-text-secondary">Diminution du temps d'intervention</p>
                    </div>
                    <div class="stat-card">
                        <div class="mb-2">
                            <span class="text-accent-secondary text-4xl font-bold">40%</span>
                        </div>
                        <p class="text-text-secondary">Augmentation de la productivité</p>
                    </div>
                    <div class="stat-card">
                        <div class="mb-2">
                            <span class="text-accent-tertiary text-4xl font-bold">60%</span>
                        </div>
                        <p class="text-text-secondary">Réduction des pannes imprévues</p>
                    </div>
                    <div class="stat-card">
                        <div class="mb-2">
                            <span class="text-accent-primary text-4xl font-bold">100%</span>
                        </div>
                        <p class="text-text-secondary">Traçabilité des interventions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <div class="cta-glow cta-glow-1"></div>
            <div class="cta-glow cta-glow-2"></div>
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-3xl mx-auto text-center">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Simplifiez votre maintenance avec notre solution GMAO</h2>
                    <p class="text-lg md:text-xl mb-8 text-gray-300">Une interface intuitive, des fonctionnalités puissantes et un support réactif pour vous accompagner au quotidien.</p>
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('login') }}" class="btn-primary px-8 py-4 rounded-lg font-semibold text-base md:text-lg">Commencer maintenant</a>
                        <a href="{{ route('client.login') }}" class="btn-secondary px-8 py-4 rounded-lg font-semibold text-base md:text-lg">Espace Client</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="container mx-auto px-4 py-8">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/logo carré tecaled impression flag.png') }}" alt="TecaLED Logo" class="h-16 mb-4">
                    <p class="text-gray-400 mb-4">© {{ date('Y') }} TecaLED - GMAO. Tous droits réservés.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Confidentialité</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Conditions d'utilisation</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">Contact</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>