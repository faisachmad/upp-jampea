<x-guest-layout>
    <div class="mb-6 text-sm text-white/70 leading-relaxed text-center">
        {{ __('Create a new secure password for your account.') }}
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email')" class="text-white/80" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <x-input-label for="password" :value="__('New Password')" class="text-white/80" />
            <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="text-white/80" />
            <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full flex justify-center py-3">
                {{ __('Update Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
