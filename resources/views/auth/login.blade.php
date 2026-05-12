<x-guest-layout>
    <x-slot name="heading">
        <h1 class="text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl">{{ __('Welcome back') }}</h1>
        <p class="mt-1 text-xs leading-snug text-slate-600 sm:text-sm">{{ __('Clients, renewals, and compliance in one workspace.') }}</p>
    </x-slot>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-3.5 sm:space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1.5 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-password-input id="password" class="mt-1.5 block w-full" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="flex items-center gap-2 pt-0.5">
            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-2 focus:ring-blue-500/30" name="remember">
            <label for="remember_me" class="text-sm text-slate-600 select-none">{{ __('Remember me') }}</label>
        </div>

        <div class="flex flex-col-reverse gap-2 pt-2 sm:flex-row sm:items-center sm:justify-between sm:gap-3">
            @if (Route::has('register'))
                <a class="text-center text-sm font-medium text-slate-600 underline decoration-slate-300 underline-offset-2 transition-colors hover:text-blue-700 sm:text-left" href="{{ route('register') }}">
                    {{ __('Create an account') }}
                </a>
            @else
                <span></span>
            @endif

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end sm:gap-3">
                @if (Route::has('password.request'))
                    <a class="text-center text-sm font-medium text-slate-600 underline decoration-slate-300 underline-offset-2 transition-colors hover:text-blue-700" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif

                <x-primary-button class="w-full shrink-0 px-6 sm:w-auto">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
