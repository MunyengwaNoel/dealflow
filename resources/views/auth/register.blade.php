<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{ gmailHint: false }">
        @csrf

        <div>
            <x-input-label for="business_name" :value="__('Business name')" />
            <x-text-input id="business_name" class="block mt-1 w-full" type="text" name="business_name" :value="old('business_name')" required autofocus autocomplete="organization" />
            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" />
            <p class="mt-1 text-xs text-slate-500">We use this for your account and may contact you about your subscription.</p>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email (optional)')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                autocomplete="email"
                @input="gmailHint = /(@gmail\\.com|@googlemail\\.com)/i.test($event.target.value || '')"
            />
            <p class="mt-1 text-xs text-slate-500">If you skip email, we create a secure login address you can replace later in your profile.</p>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            <div
                x-show="gmailHint"
                x-transition.opacity.duration.200ms
                x-cloak
                class="mt-3 rounded-xl border border-amber-200/80 bg-amber-50 px-3 py-2.5 text-sm leading-snug text-amber-950 shadow-sm ring-1 ring-amber-100"
                role="status"
            >
                <p class="font-semibold text-amber-900">Prefer a professional .co.zw address?</p>
                <p class="mt-1 text-amber-900/90">
                    Contact <a href="mailto:info@softurf.co.zw" class="font-semibold text-amber-950 underline decoration-amber-600 underline-offset-2 hover:text-amber-800">info@softurf.co.zw</a>
                    or message <a href="https://wa.me/263718617038" class="font-semibold text-amber-950 underline decoration-amber-600 underline-offset-2 hover:text-amber-800" target="_blank" rel="noopener noreferrer">071 861 7038</a> to get a .co.zw email for your business.
                </p>
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-slate-500 hover:text-slate-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-slate-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Create account') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
