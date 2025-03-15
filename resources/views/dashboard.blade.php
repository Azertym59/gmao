<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glassmorphism overflow-hidden shadow-lg rounded-xl">
                <div class="p-6 text-text-primary">
                    <h3 class="text-lg font-semibold mb-4">Bienvenue dans GMAO TecaLED</h3>
                    <p class="mb-4">Utilisez les options de navigation pour gérer vos chantiers, produits et interventions.</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <a href="{{ route('chantiers.index') }}" class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-blue hover:border-opacity-30 transition-all hover:bg-accent-blue/5">
                            <h4 class="text-accent-blue font-medium mb-2">Gestion des chantiers</h4>
                            <p class="text-gray-400 text-sm">Créez et suivez l'avancement de vos chantiers de réparation</p>
                        </a>
                        <a href="{{ route('produits.index') }}" class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-green hover:border-opacity-30 transition-all hover:bg-accent-green/5">
                            <h4 class="text-accent-green font-medium mb-2">Gestion des produits</h4>
                            <p class="text-gray-400 text-sm">Consultez et gérez les produits en cours de réparation</p>
                        </a>
                        <a href="{{ route('interventions.index') }}" class="glassmorphism p-4 rounded-lg border border-opacity-10 border-accent-purple hover:border-opacity-30 transition-all hover:bg-accent-purple/5">
                            <h4 class="text-accent-purple font-medium mb-2">Interventions techniques</h4>
                            <p class="text-gray-400 text-sm">Gérez les interventions à réaliser par les techniciens</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
