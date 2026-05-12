<x-guest-layout>
    <x-slot name="heading">
        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-[1.65rem]">{{ __('Verify your email') }}</h1>
        <p class="mt-2 text-sm leading-relaxed text-slate-600">
            {{ __('Thanks for signing up! Before getting started, please verify your email using the link we sent. If you did not receive it, we can send another.') }}
        </p>
    </x-slot>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900" role="status">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full sm:w-auto">
                {{ __('Resend verification email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-center text-sm font-medium text-slate-600 underline decoration-slate-300 underline-offset-2 transition-colors hover:text-slate-900 sm:w-auto">
                {{ __('Log out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
