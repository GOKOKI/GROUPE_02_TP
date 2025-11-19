<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Créer un compte')" :description="__('Entrez vos informations pour commencer')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Nom complet')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Nom complet')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Adresse Email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Mot de passe')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Mot de passe')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirmer le mot de passe')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirmer le mot de passe')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full bg-red-600 hover:bg-red-700 border-red-600 hover:border-red-700 text-white">
                    {{ __('Créer mon compte') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Vous avez déjà un compte ?') }}</span>
            <flux:link :href="route('login')" class="text-red-600 hover:text-red-700 dark:text-red-400" wire:navigate>{{ __('Se connecter') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>
