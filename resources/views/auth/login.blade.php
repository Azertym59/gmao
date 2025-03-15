<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-white">{{ __('Connectez-vous') }}</h2>
        <p class="text-gray-400 mt-2">{{ __('Entrez vos identifiants pour accéder à votre compte') }}</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" class="text-white" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-800 border-gray-700 text-white" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" class="text-white" :value="__('Mot de passe')" />

            <x-text-input id="password" class="block mt-1 w-full bg-gray-800 border-gray-700 text-white"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-gray-800 border-gray-700 text-accent-blue focus:ring-accent-blue" name="remember">
                <span class="ms-2 text-sm text-white">{{ __('Se souvenir de moi') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-accent-blue hover:text-blue-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-blue" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 bg-accent-blue hover:bg-blue-600">
                {{ __('Connexion') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
