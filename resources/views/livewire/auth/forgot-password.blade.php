<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Mot de passe oublié')" :description="__('Entrez votre email pour recevoir un lien de réinitialisation')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Adresse Email')"
                type="email"
                required
                autofocus
                placeholder="email@example.com"
            />

            <flux:button variant="primary" type="submit" class="w-full bg-red-600 hover:bg-red-700 border-red-600 hover:border-red-700 text-white" data-test="email-password-reset-link-button">
                {{ __('Envoyer le lien de réinitialisation') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Ou, retourner à la') }}</span>
            <flux:link :href="route('login')" class="text-red-600 hover:text-red-700 dark:text-red-400" wire:navigate>{{ __('connexion') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>
