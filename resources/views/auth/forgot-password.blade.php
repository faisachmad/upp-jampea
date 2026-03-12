<x-guest-layout>
    <div class="mb-6 text-sm text-white/70 leading-relaxed text-center">
        {{ __('Enter your registered email address and we will send you a password reset link.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6 text-center" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email')" class="text-white/80" />
            <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full flex justify-center py-3">
                {{ __('Send Reset Link') }}
            </x-primary-button>
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-white/60 hover:text-white transition-colors">
                &larr; Back to Login
            </a>
        </div>
    </form>
</x-guest-layout>
