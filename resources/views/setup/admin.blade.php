<x-guest-layout>
    <div class="glassmorphism overflow-hidden shadow-lg rounded-xl p-6 max-w-md mx-auto mt-10">
        <h2 class="text-xl font-semibold text-white text-center mb-6">
            Configuration initiale - Création du compte administrateur
        </h2>

        <p class="text-gray-300 mb-6">
            Bienvenue dans votre application GMAO pour écrans LED. Aucun compte administrateur n'a été détecté.
            Veuillez créer le premier compte administrateur pour commencer.
        </p>

        <form method="POST" action="{{ route('setup.admin.store') }}">
            @csrf

            <!-- Nom -->
            <div>
                <x-input-label for="name" :value="__('Nom')" class="text-gray-300" />
                <x-text-input id="name" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" class="text-gray-300" />
                <x-text-input id="email" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-300" />
                <x-text-input id="password" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-300" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-700 border-gray-600 text-white" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-primary-button class="w-full justify-center py-3 bg-accent-blue hover:bg-blue-600">
                    {{ __('Créer le compte administrateur') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>