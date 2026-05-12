<x-guest-layout>
    <x-slot name="heading">
        <h1 class="text-xl font-extrabold tracking-tight text-slate-900 sm:text-2xl">{{ __('Create your workspace') }}</h1>
        <p class="mt-1 text-xs leading-snug text-slate-600 sm:text-sm sm:leading-relaxed">{{ __('Sign-in, renewals, documents, and billing—set up in one step.') }}</p>
    </x-slot>

    <form method="POST" action="{{ route('register') }}" class="space-y-3.5 sm:space-y-4" x-data="{ gmailHint: false }">
        @csrf

        <div class="grid gap-3.5 sm:grid-cols-2 sm:gap-4">
            <div>
                <x-input-label for="business_name" :value="__('Business name')" />
                <x-text-input id="business_name" class="mt-1.5 block w-full" type="text" name="business_name" :value="old('business_name')" required autofocus autocomplete="organization" />
                <x-input-error :messages="$errors->get('business_name')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="phone" :value="__('Phone number')" />
                <x-text-input id="phone" class="mt-1.5 block w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" />
                <p class="mt-1 text-[11px] leading-snug text-slate-500">{{ __('Account & subscription contact.') }}</p>
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email (optional)')" />
            <x-text-input
                id="email"
                class="mt-1.5 block w-full"
                type="email"
                name="email"
                :value="old('email')"
                autocomplete="email"
                @input="gmailHint = /(@gmail\\.com|@googlemail\\.com)/i.test($event.target.value || '')"
            />
            <p class="mt-1 text-[11px] leading-snug text-slate-500">{{ __('Leave blank for an internal login you can change later in your profile.') }}</p>
            <x-input-error :messages="$errors->get('email')" class="mt-1" />

            <div
                x-show="gmailHint"
                x-transition.opacity.duration.200ms
                x-cloak
                class="mt-2 rounded-lg border border-amber-200/90 bg-amber-50 px-2.5 py-2 text-[11px] leading-snug text-amber-950 shadow-sm ring-1 ring-amber-100/80 sm:text-xs"
                role="status"
            >
                <p class="font-semibold text-amber-900">{{ __('Prefer a professional .co.zw address?') }}</p>
                <p class="mt-0.5 text-amber-900/90">
                    <a href="mailto:info@softurf.co.zw" class="font-semibold underline decoration-amber-600 underline-offset-1 hover:text-amber-800">info@softurf.co.zw</a>
                    ·
                    <a href="https://wa.me/263718617039" class="font-semibold underline decoration-amber-600 underline-offset-1 hover:text-amber-800" target="_blank" rel="noopener noreferrer">071 861 7038</a>
                </p>
            </div>
        </div>

        <div class="grid gap-3.5 sm:grid-cols-2 sm:gap-4">
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-password-input id="password" class="mt-1.5 block w-full" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm password')" />
                <x-password-input id="password_confirmation" class="mt-1.5 block w-full" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between sm:gap-3 sm:pt-5">
            <a class="text-center text-sm font-medium text-slate-600 underline decoration-slate-300 underline-offset-2 transition-colors hover:text-blue-700 sm:text-left" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="w-full px-6 sm:w-auto">
                {{ __('Create account') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
