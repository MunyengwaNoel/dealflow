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

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
            {{ $greet }}, {{ $first }} 👋
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Here's what's happening with your business today &mdash; {{ $date }}.
        </p>
    </div>

    <div class="flex items-center gap-3">
        @if(!$isPro)
            <a href="#"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500
                      text-white text-xs font-bold shadow-md shadow-blue-600/25 transition-all hover:scale-105">
                <x-heroicon-m-bolt class="w-3.5 h-3.5" />
                Upgrade to Pro
            </a>
        @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold">
                <x-heroicon-m-check-badge class="w-3.5 h-3.5" />
                Pro Plan
            </span>
        @endif
    </div>
</div>
