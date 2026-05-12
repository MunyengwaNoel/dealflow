@php
    $user    = auth()->user();
    $tenant  = app('tenant') ?? $user?->tenant;
    $name    = $user?->name ?? 'there';
    $first   = explode(' ', $name)[0];
    $hour    = now()->hour;
    $greet   = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    $isPro   = $tenant?->isPro();
    $date    = now()->format('l, M j, Y');
@endphp

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
            {{ $greet }}, {{ $first }} 👋
        </h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
            Here's what's happening with your business today &mdash; {{ $date }}.
        </p>
    </div>

    <div class="flex items-center gap-3">
        @if(!$isPro)
            <a href="#"
               class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-blue-600/25 transition-all hover:scale-105 hover:bg-blue-500">
                <x-heroicon-m-bolt class="h-3.5 w-3.5" />
                Upgrade to Pro
            </a>
        @else
            <span class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-800 dark:border-emerald-700/80 dark:bg-emerald-950/60 dark:text-emerald-300">
                <x-heroicon-m-check-badge class="h-3.5 w-3.5" />
                Pro Plan
            </span>
        @endif
    </div>
</div>
