<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DealFlow Pro') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-nunito antialiased min-h-screen bg-[#0A0F1E]">

        {{-- Background decoration --}}
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-blue-700/15 rounded-full blur-3xl -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-indigo-700/10 rounded-full blur-3xl translate-y-1/2"></div>
        </div>

        <div class="relative min-h-screen flex flex-col">

            {{-- Logo / brand at top --}}
            <div class="flex justify-center pt-8">
                <a href="/" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg shadow-blue-600/30 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 4a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4z"/>
                            <path fill-rule="evenodd" d="M2 9.5A.5.5 0 012.5 9h15a.5.5 0 010 1h-15A.5.5 0 012 9.5zm0 3A.5.5 0 012.5 12h15a.5.5 0 010 1h-15A.5.5 0 012 12zm0 3A.5.5 0 012.5 15h10a.5.5 0 010 1h-10A.5.5 0 012 15z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xl font-black text-white tracking-tight">{{ config('app.name', 'DealFlow Pro') }}</span>
                </a>
            </div>

            {{-- Card --}}
            <div class="flex flex-1 items-center justify-center px-4 py-8">
                @hasSection('content')
                    @yield('content')
                @else
                    <div class="w-full max-w-sm">
                        <div class="bg-white rounded-2xl shadow-2xl shadow-black/40 px-8 py-8">
                            {{ $slot }}
                        </div>
                        <p class="mt-6 text-center text-xs text-slate-500">
                            <a href="/" class="hover:text-slate-300 transition-colors">← Back to homepage</a>
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </body>
</html>
