<x-guest-layout>
    <x-slot name="heading">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[1.65rem]">{{ __('Forgot password') }}</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ __('No problem. Enter your email and we will send you a reset link to choose a new password.') }}</p>
    </x-slot>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="border-t border-slate-100 pt-6">
            <x-primary-button class="w-full sm:w-auto">
                {{ __('Email password reset link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
