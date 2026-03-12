<x-guest-layout>
    <div class="mb-6 text-sm text-white/70 leading-relaxed text-center">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div class="space-y-1">
            <x-input-label for="password" :value="__('Password')" class="text-white/80" />
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full flex justify-center py-3">
                {{ __('Confirm Access') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
