<x-guest-layout>
    <x-slot name="heading">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[1.65rem]">{{ __('Confirm password') }}</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ __('This is a secure area. Please confirm your password before continuing.') }}</p>
    </x-slot>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-password-input id="password" class="mt-2 block w-full" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="border-t border-slate-100 pt-6">
            <x-primary-button class="w-full sm:w-auto">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
