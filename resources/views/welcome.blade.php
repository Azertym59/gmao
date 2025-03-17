<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GMAO TecaLED - Gestion de Maintenance Assistée par Ordinateur</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Instrument Sans', sans-serif;
                background-color: #f8f9fa;
                color: #333;
            }
            .hero-section {
                background: linear-gradient(135deg, #2c3e50, #4a6491);
                color: white;
                padding: 100px 0;
                text-align: center;
            }
            .features-section {
                padding: 80px 0;
                background-color: white;
            }
            .feature-card {
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                height: 100%;
                transition: transform 0.3s ease;
            }
            .feature-card:hover {
                transform: translateY(-5px);
            }
            .footer {
                background-color: #2c3e50;
                color: white;
                padding: 30px 0;
            }
        </style>
    </head>
    <body>
        <div class="hero-section">
            <div class="container mx-auto px-4">
                <img src="{{ asset('images/Logo rectangle V2.png') }}" alt="TecaLED Logo" class="h-64 mx-auto mb-8">
                <h1 class="text-4xl font-bold mb-4">GMAO TecaLED</h1>
                <p class="text-xl mb-8">Gestion de Maintenance Assistée par Ordinateur</p>
                @if (auth()->check() && auth()->user()->client_id)
                <p class="text-2xl mb-8 text-white font-medium">Bienvenue {{ auth()->user()->client->civilite }} {{ auth()->user()->client->getNomCompletSansDoublonAttribute() }}</p>
                @endif
                <div class="space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-white text-blue-800 hover:bg-blue-100 px-6 py-3 rounded-lg font-medium transition">Accéder au tableau de bord</a>
                        @else
                            <a href="{{ route('login') }}" class="bg-white text-blue-800 hover:bg-blue-100 px-6 py-3 rounded-lg font-medium transition">Connexion</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-transparent border border-white text-white hover:bg-white hover:text-blue-800 px-6 py-3 rounded-lg font-medium transition">Inscription</a>
                            @endif
                        @endauth
                    @endif
                    <a href="{{ route('client.login') }}" class="bg-green-600 text-white hover:bg-green-700 px-6 py-3 rounded-lg font-medium transition">Espace Client</a>
                </div>
            </div>
        </div>

        <div class="features-section">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Fonctionnalités principales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="feature-card bg-white">
                        <div class="text-4xl text-blue-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Gestion des interventions</h3>
                        <p class="text-gray-600">Planifiez, suivez et documentez toutes vos interventions de maintenance pour optimiser vos processus.</p>
                    </div>
                    
                    <div class="feature-card bg-white">
                        <div class="text-4xl text-blue-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Gestion des stocks</h3>
                        <p class="text-gray-600">Suivez vos pièces détachées, gérez les mouvements de stock et évitez les ruptures.</p>
                    </div>
                    
                    <div class="feature-card bg-white">
                        <div class="text-4xl text-blue-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Rapports détaillés</h3>
                        <p class="text-gray-600">Générez des rapports personnalisés pour analyser les performances et prendre des décisions éclairées.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12 bg-gray-100">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-3xl font-bold mb-8">Simplifiez votre maintenance avec notre solution GMAO</h2>
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-8 py-3 rounded-lg font-medium transition">Commencer maintenant</a>
                    <a href="{{ route('client.login') }}" class="bg-green-600 text-white hover:bg-green-700 px-8 py-3 rounded-lg font-medium transition">Espace Client</a>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container mx-auto px-4 text-center">
                <img src="{{ asset('images/logo carré tecaled impression flag.png') }}" alt="TecaLED Logo" class="h-16 mx-auto mb-4">
                <p>&copy; {{ date('Y') }} TecaLED - GMAO. Tous droits réservés.</p>
            </div>
        </footer>
    </body>
</html>