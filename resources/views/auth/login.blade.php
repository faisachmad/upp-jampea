<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email')" class="text-white/80" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="text-white/80" />
                @if (Route::has('password.request'))
                    <a class="text-xs text-white/60 hover:text-white" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-white/20 bg-white/5 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-0" name="remember">
                <span class="ms-2 text-sm text-white/60">{{ __('Keep me logged in') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full flex justify-center py-3">
                {{ __('Secure Login') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
