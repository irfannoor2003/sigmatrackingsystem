<x-guest-layout>
    <div class="mb-6">
        <h2 class="font-display font-bold text-[1.5rem] tracking-tight text-[var(--text)]">Confirm password</h2>
        <p class="text-sm mt-1 muted">Secure area — confirm your password to continue</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-5">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>