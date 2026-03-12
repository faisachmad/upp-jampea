<x-guest-layout>
    <div class="mb-6 text-sm text-white/70 leading-relaxed text-center">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-medium text-sm text-green-400 text-center">
            {{ __('A new verification link has been sent to your email address.') }}
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full flex justify-center py-3">
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm text-white/60 hover:text-white transition-colors underline underline-offset-4">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
