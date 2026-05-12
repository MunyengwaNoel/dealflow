<x-guest-layout>
    <x-slot name="heading">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[1.65rem]">{{ __('Reset password') }}</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ __('Choose a new password for your account.') }}</p>
    </x-slot>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-password-input id="password" class="mt-2 block w-full" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-password-input id="password_confirmation" class="mt-2 block w-full" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="border-t border-slate-100 pt-6">
            <x-primary-button class="w-full sm:w-auto">
                {{ __('Reset password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
