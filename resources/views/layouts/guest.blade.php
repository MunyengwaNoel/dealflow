<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DealFlow Pro') }} — {{ __('Account') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen antialiased bg-[#050816] font-landing text-slate-900">

        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_90%_60%_at_50%_-10%,rgba(56,189,248,0.14),transparent)]"></div>
            <div class="absolute left-1/4 top-0 h-[420px] w-[420px] -translate-y-1/2 rounded-full bg-blue-600/20 blur-[100px]"></div>
            <div class="absolute bottom-0 right-1/4 h-[360px] w-[360px] translate-y-1/3 rounded-full bg-indigo-600/15 blur-[90px]"></div>
            <div class="absolute inset-0 opacity-[0.2] bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDBoNjB2NjBIMHoiLz48cGF0aCBkPSJNNjAgMEgwdjYwaDYwVjB6TTEgMWg1OHY1OEgxVjF6IiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDMpIi8+PC9nPjwvc3ZnPg==')]"></div>
        </div>

        <div class="relative flex min-h-screen flex-col">
            <header class="flex justify-center px-4 pt-5 pb-1 sm:pt-7 sm:pb-2">
                <a href="/" class="group flex items-center gap-3 rounded-2xl border border-white/10 bg-white/[0.04] px-3 py-2 shadow-lg shadow-black/20 backdrop-blur-sm transition-all hover:border-white/15 hover:bg-white/[0.07]">
                    <img src="{{ asset('images/dealflow-logo-on-dark.svg') }}" alt="{{ config('app.name', 'Deal Flow') }}" class="h-7 w-auto max-h-8 max-w-[min(52vw,220px)] object-contain object-left sm:h-8" width="268" height="86" loading="eager" decoding="async">
                    <span class="hidden text-left leading-tight sm:block">
                        <span class="block text-[11px] font-medium text-cyan-200/90">{{ __('Compliance & operations') }}</span>
                    </span>
                </a>
            </header>

            <main class="flex flex-1 items-center justify-center px-4 py-5 sm:px-6 sm:py-8">
                @hasSection('content')
                    @yield('content')
                @else
                    <div class="w-full max-w-md">
                        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-2xl shadow-slate-900/40 ring-1 ring-slate-900/[0.04] sm:p-6">
                            @isset($heading)
                                <div class="mb-5 border-b border-slate-100 pb-4 sm:mb-6 sm:pb-5">
                                    {{ $heading }}
                                </div>
                            @endisset

                            {{ $slot }}
                        </div>

                        <p class="mt-5 text-center text-sm text-slate-300">
                            <a href="/" class="font-medium text-slate-300 underline decoration-slate-500/60 underline-offset-2 transition-colors hover:text-white">{{ __('← Back to homepage') }}</a>
                        </p>
                    </div>
                @endif
            </main>
        </div>
    </body>
</html>
