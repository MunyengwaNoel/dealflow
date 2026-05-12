<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DealFlow Pro — Company registration, renewals & compliance in one place</title>
    <meta name="description" content="Guide clients from company registration through domain renewals, email subscription alerts, and document expiry. DealFlow Pro is your operating system for compliance-aware service businesses.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-landing antialiased bg-slate-50 text-slate-900">

{{-- NAV --}}
<header x-data="{ scrolled: false, mobileOpen: false }"
        @scroll.window="scrolled = window.scrollY > 16"
        :class="scrolled ? 'bg-slate-950/90 backdrop-blur-xl border-b border-white/5 shadow-lg shadow-black/20' : 'bg-transparent border-b border-transparent'"
        class="fixed inset-x-0 top-0 z-50 transition-all duration-300">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16 md:h-[4.25rem]">
            <a href="/" class="flex items-center gap-2.5 group shrink-0">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-400 via-blue-600 to-indigo-700 flex items-center justify-center shadow-lg shadow-blue-600/25 group-hover:scale-[1.03] transition-transform">
                    <svg class="w-[18px] h-[18px] text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M2 4a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4z"/>
                        <path fill-rule="evenodd" d="M2 9.5A.5.5 0 012.5 9h15a.5.5 0 010 1h-15A.5.5 0 012 9.5zm0 3A.5.5 0 012.5 12h15a.5.5 0 010 1h-15A.5.5 0 012 12zm0 3A.5.5 0 012.5 15h10a.5.5 0 010 1h-10A.5.5 0 012 15z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="text-[15px] font-extrabold tracking-tight text-white">DealFlow</span>
                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-cyan-300/90">Pro</span>
                </div>
            </a>

            <nav class="hidden lg:flex items-center gap-0.5 rounded-full bg-white/5 border border-white/10 px-1 py-1">
                <a href="#process" :class="scrolled ? 'text-slate-300 hover:text-white' : 'text-slate-200 hover:text-white'" class="px-3.5 py-1.5 text-sm font-medium rounded-full transition-colors">Registration</a>
                <a href="#renewals" :class="scrolled ? 'text-slate-300 hover:text-white' : 'text-slate-200 hover:text-white'" class="px-3.5 py-1.5 text-sm font-medium rounded-full transition-colors">Renewals</a>
                <a href="#alerts" :class="scrolled ? 'text-slate-300 hover:text-white' : 'text-slate-200 hover:text-white'" class="px-3.5 py-1.5 text-sm font-medium rounded-full transition-colors">Alerts</a>
                <a href="#features" :class="scrolled ? 'text-slate-300 hover:text-white' : 'text-slate-200 hover:text-white'" class="px-3.5 py-1.5 text-sm font-medium rounded-full transition-colors">Platform</a>
                <a href="#pricing" :class="scrolled ? 'text-slate-300 hover:text-white' : 'text-slate-200 hover:text-white'" class="px-3.5 py-1.5 text-sm font-medium rounded-full transition-colors">Pricing</a>
            </nav>

            <div class="hidden md:flex items-center gap-2 shrink-0">
                <a href="/admin/login"
                   class="px-4 py-2 text-sm font-semibold text-slate-200 hover:text-white transition-colors rounded-lg">
                    Sign in
                </a>
                <a href="/register"
                   class="px-5 py-2.5 text-sm font-bold text-slate-950 bg-gradient-to-r from-cyan-300 to-blue-400 rounded-xl shadow-lg shadow-cyan-500/20 hover:shadow-cyan-400/30 hover:brightness-105 transition-all">
                    Start free
                </a>
            </div>

            <button type="button" @click="mobileOpen = !mobileOpen"
                    class="md:hidden p-2 rounded-lg text-white border border-white/10 bg-white/5"
                    aria-expanded="false" :aria-expanded="mobileOpen">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div x-show="mobileOpen" x-transition x-cloak class="md:hidden pb-4 border-t border-white/10 mt-1">
            <div class="flex flex-col gap-0.5 pt-3">
                <a href="#process" @click="mobileOpen=false" class="px-3 py-2.5 text-sm font-medium text-slate-200 rounded-lg hover:bg-white/5">Registration</a>
                <a href="#renewals" @click="mobileOpen=false" class="px-3 py-2.5 text-sm font-medium text-slate-200 rounded-lg hover:bg-white/5">Renewals</a>
                <a href="#alerts" @click="mobileOpen=false" class="px-3 py-2.5 text-sm font-medium text-slate-200 rounded-lg hover:bg-white/5">Alerts</a>
                <a href="#features" @click="mobileOpen=false" class="px-3 py-2.5 text-sm font-medium text-slate-200 rounded-lg hover:bg-white/5">Platform</a>
                <a href="#pricing" @click="mobileOpen=false" class="px-3 py-2.5 text-sm font-medium text-slate-200 rounded-lg hover:bg-white/5">Pricing</a>
                <div class="flex gap-2 mt-3 pt-3 border-t border-white/10">
                    <a href="/admin/login" class="flex-1 text-center py-2.5 text-sm font-semibold text-white border border-white/15 rounded-xl">Sign in</a>
                    <a href="/register" class="flex-1 text-center py-2.5 text-sm font-bold text-slate-950 bg-gradient-to-r from-cyan-300 to-blue-400 rounded-xl">Start free</a>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- HERO --}}
<section class="relative min-h-[100svh] flex flex-col overflow-hidden bg-[#050816] text-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_-20%,rgba(56,189,248,0.18),transparent)]"></div>
    <div class="pointer-events-none absolute inset-0 opacity-[0.35] bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDBoNjB2NjBIMHoiLz48cGF0aCBkPSJNNjAgMEgwdjYwaDYwVjB6TTEgMWg1OHY1OEgxVjF6IiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDMpIi8+PC9nPjwvc3ZnPg==')]"></div>
    <div class="pointer-events-none absolute top-1/3 -left-32 w-96 h-96 rounded-full bg-blue-600/20 blur-[100px]"></div>
    <div class="pointer-events-none absolute bottom-0 right-0 w-[28rem] h-[28rem] rounded-full bg-indigo-600/15 blur-[110px] translate-y-1/3"></div>

    <div class="relative flex-1 flex flex-col max-w-6xl mx-auto w-full px-4 sm:px-6 pt-28 pb-12 md:pt-32 md:pb-16">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-10 xl:gap-14 flex-1">
            <div class="flex-1 text-center lg:text-left max-w-xl lg:max-w-none">
                <p class="inline-flex items-center gap-2 rounded-full border border-cyan-400/25 bg-cyan-400/10 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.16em] text-cyan-200 mb-5">
                    <span class="size-1.5 rounded-full bg-cyan-300 animate-pulse" aria-hidden="true"></span>
                    From incorporation to every renewal
                </p>

                <h1 class="text-[2.35rem] sm:text-5xl lg:text-[3.25rem] xl:text-[3.5rem] font-extrabold leading-[1.08] tracking-tight text-white">
                    Run company registration and
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-200 via-sky-300 to-blue-400"> every compliance deadline</span>
                    in one calm workspace.
                </h1>

                <p class="mt-5 text-base sm:text-lg text-slate-400 leading-relaxed max-w-xl mx-auto lg:mx-0">
                    Onboard clients with a guided registration workflow, watch domains and registrar dates, surface email subscription renewals, and get ahead of document expiry—before anyone has to chase you.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row items-stretch sm:items-center justify-center lg:justify-start gap-3">
                    <a href="/register"
                       class="inline-flex justify-center items-center px-7 py-3.5 rounded-xl font-bold text-slate-950 bg-gradient-to-r from-cyan-300 to-blue-400 shadow-xl shadow-cyan-500/20 hover:shadow-cyan-400/35 hover:brightness-105 transition-all">
                        Start for free
                    </a>
                    <a href="/admin/login"
                       class="inline-flex justify-center items-center px-7 py-3.5 rounded-xl font-semibold text-white border border-white/15 bg-white/[0.04] hover:bg-white/[0.08] transition-colors">
                        Open live demo
                    </a>
                </div>

                <ul class="mt-8 flex flex-col sm:flex-row flex-wrap items-center justify-center lg:justify-start gap-x-6 gap-y-2 text-sm text-slate-500">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Registration checklists &amp; handoffs
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Domain &amp; subscription lead times
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Document expiry radar
                    </li>
                </ul>
            </div>

            {{-- Product preview --}}
            <div class="flex-1 w-full max-w-lg lg:max-w-none">
                <div class="relative mx-auto lg:mr-0 lg:ml-auto max-w-[440px] lg:max-w-[480px]">
                    <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-cyan-500/20 via-blue-600/10 to-indigo-600/20 blur-2xl"></div>

                    <div class="relative rounded-2xl border border-white/10 bg-slate-900/80 shadow-2xl shadow-black/50 backdrop-blur-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-3 py-2.5 bg-slate-950/80 border-b border-white/5">
                            <div class="flex gap-1.5" aria-hidden="true">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500/80"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400/80"></span>
                            </div>
                            <div class="flex-1 min-w-0 mx-2">
                                <div class="rounded-md bg-slate-800/80 border border-white/5 px-2 py-1 text-[10px] text-slate-400 text-center truncate">app.dealflow.pro / compliance</div>
                            </div>
                        </div>

                        <div class="flex" style="min-height: 360px;">
                            <aside class="w-[38%] max-w-[168px] shrink-0 border-r border-white/5 bg-slate-950/50 p-2 flex flex-col gap-1">
                                <div class="flex items-center gap-2 px-2 py-2 rounded-lg bg-white/[0.04] border border-white/5">
                                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-cyan-400 to-blue-600 shrink-0"></div>
                                    <span class="text-[10px] font-bold text-white truncate">DealFlow Pro</span>
                                </div>
                                @foreach(['Dashboard','Clients','Orders','Documents','Alerts'] as $i => $label)
                                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-[10px] font-medium {{ $i === 4 ? 'bg-cyan-500/15 text-cyan-200 border border-cyan-400/20' : 'text-slate-500' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $i === 4 ? 'bg-cyan-400' : 'bg-slate-600' }}"></span>
                                        {{ $label }}
                                    </div>
                                @endforeach
                                <div class="mt-auto rounded-lg border border-amber-500/25 bg-amber-500/10 p-2">
                                    <p class="text-[9px] font-bold text-amber-100">4 items this week</p>
                                    <p class="text-[8px] text-amber-200/80 mt-0.5">Renewals &amp; expiries</p>
                                </div>
                            </aside>

                            <div class="flex-1 min-w-0 bg-slate-900 p-3 flex flex-col gap-2">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="text-[11px] font-bold text-white">Compliance pulse</p>
                                        <p class="text-[9px] text-slate-500">Next 45 days</p>
                                    </div>
                                    <span class="text-[9px] font-semibold px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-400/20">On track</span>
                                </div>

                                <div class="space-y-1.5">
                                    @foreach([
                                        ['Domain','acmecorp.co.zw','Renews in 32 days','cyan'],
                                        ['Email','Microsoft 365 · 12 seats','Subscription in 18 days','violet'],
                                        ['Document','Tax clearance certificate','Expires in 24 days','amber'],
                                        ['Registry','Annual return filing','Due in 41 days','blue'],
                                    ] as $row)
                                    <div class="flex items-center gap-2 rounded-lg border border-white/5 bg-slate-950/60 px-2 py-1.5">
                                        <div class="w-7 h-7 rounded-md flex items-center justify-center shrink-0
                                            @if($row[3]==='cyan') bg-cyan-500/15 text-cyan-300
                                            @elseif($row[3]==='violet') bg-violet-500/15 text-violet-300
                                            @elseif($row[3]==='amber') bg-amber-500/15 text-amber-300
                                            @else bg-blue-500/15 text-blue-300 @endif">
                                            <span class="text-[9px] font-extrabold">{{ strtoupper(substr($row[0],0,1)) }}</span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[9px] font-semibold text-slate-300 truncate">{{ $row[1] }}</p>
                                            <p class="text-[8px] text-slate-500 truncate">{{ $row[2] }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="mt-auto rounded-lg border border-white/10 bg-gradient-to-br from-slate-800/80 to-slate-900 p-2">
                                    <p class="text-[9px] font-bold text-white">Suggested action</p>
                                    <p class="text-[8px] text-slate-400 mt-0.5">Queue registrar renewal &amp; notify finance for email billing.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating cards --}}
                    <div class="absolute -right-2 sm:right-0 top-8 translate-x-1/4 sm:translate-x-0 lg:-right-6 lg:translate-x-0 w-[200px] rounded-xl border border-white/10 bg-slate-950/95 p-3 shadow-xl hidden sm:block backdrop-blur-md">
                        <div class="flex items-start gap-2">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/15 flex items-center justify-center shrink-0 text-amber-300">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">3 documents</p>
                                <p class="text-[11px] text-slate-400 mt-0.5 leading-snug">Licences &amp; tax certs need review before month-end.</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -left-2 sm:left-0 bottom-16 -translate-x-1/4 sm:translate-x-0 lg:-left-5 lg:translate-x-0 w-[188px] rounded-xl border border-cyan-400/20 bg-slate-950/95 p-3 shadow-xl hidden sm:block backdrop-blur-md">
                        <p class="text-[10px] font-bold text-cyan-200 uppercase tracking-wider">Domain watch</p>
                        <p class="text-lg font-extrabold text-white mt-1">45 days</p>
                        <p class="text-[11px] text-slate-400">Earliest renewal window across 6 client zones.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-10">
            <a href="#process" class="group flex flex-col items-center gap-1 text-slate-500 hover:text-slate-300 transition-colors">
                <span class="text-[10px] font-bold uppercase tracking-[0.25em]">See the workflow</span>
                <svg class="w-4 h-4 motion-safe:group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="border-y border-slate-200 bg-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach([
                ['500+','Active compliance programs'],
                ['48','Average days of renewal lead time'],
                ['12k+','Tracked obligations'],
                ['4.9','Partner satisfaction'],
            ] as $cell)
            <div>
                <p class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $cell[0] }}</p>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">{{ $cell[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- BENTO: pillars --}}
<section id="renewals" class="scroll-mt-24 py-20 md:py-28 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="max-w-2xl mb-12 md:mb-16">
            <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-3">Why teams switch</p>
            <h2 class="text-3xl sm:text-4xl md:text-[2.75rem] font-extrabold text-slate-900 tracking-tight leading-tight">
                Your clients do not miss revenue targets—they miss <span class="text-blue-600">renewal windows</span> and <span class="text-blue-600">paperwork cliffs</span>.
            </h2>
            <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                DealFlow Pro is the advert-ready story: one place to sell, deliver, and prove you are on top of every statutory and vendor date.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 md:gap-5">
            <article class="md:col-span-7 rounded-2xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/25">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    <h3 class="text-xl font-bold text-slate-900">Company registration, orchestrated</h3>
                </div>
                <p class="text-slate-600 leading-relaxed">
                    Capture statutory names, beneficial owners, and filing packs in a guided flow. Assign internal reviewers, attach registrar receipts, and move the client from “intake” to “live entity” without another spreadsheet tab.
                </p>
                <ul class="mt-5 grid sm:grid-cols-2 gap-3 text-sm text-slate-700">
                    <li class="flex gap-2 rounded-xl bg-slate-50 border border-slate-100 px-3 py-2.5">
                        <span class="text-blue-600 font-bold">1.</span> Structured data you can reuse for quotes &amp; orders
                    </li>
                    <li class="flex gap-2 rounded-xl bg-slate-50 border border-slate-100 px-3 py-2.5">
                        <span class="text-blue-600 font-bold">2.</span> Clear client-facing milestones &amp; due dates
                    </li>
                </ul>
            </article>

            <article class="md:col-span-5 rounded-2xl border border-slate-200 bg-gradient-to-br from-slate-900 to-slate-950 p-6 md:p-8 text-white shadow-xl">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-400/20 text-cyan-200 border border-cyan-400/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </span>
                    <h3 class="text-xl font-bold">Domain renewal notifications</h3>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Sync registrar dates and DNS health signals. Escalate at 60 / 30 / 7 days with owner, billing contact, and auto-generated renewal tasks—so “we forgot the domain” never becomes your brand story.
                </p>
            </article>

            <article class="md:col-span-5 rounded-2xl border border-slate-200 bg-white p-6 md:p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <h3 class="text-xl font-bold text-slate-900">Email subscription alerts</h3>
                </div>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Track Microsoft 365, Google Workspace, and bundled mail packs. Surface seat counts, annual true-ups, and trial conversions in the same alert stream as domains—finance sees one ledger of what is about to bill.
                </p>
            </article>

            <article class="md:col-span-7 rounded-2xl border border-amber-200/60 bg-gradient-to-br from-amber-50 to-white p-6 md:p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500 text-white shadow-lg shadow-amber-500/30">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H5a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                    </span>
                    <h3 class="text-xl font-bold text-slate-900">Document expiry you cannot ignore</h3>
                </div>
                <p class="text-slate-700 text-sm leading-relaxed">
                    Tax clearances, industry licences, ID copies, and board resolutions live in a vault with rolling expiry. Alerts land in-app and in digest emails with “who owns the renewal” so nothing dies in a shared drive folder named <em>Final_final_v3</em>.
                </p>
            </article>
        </div>
    </div>
</section>

{{-- PROCESS TIMELINE --}}
<section id="process" class="scroll-mt-24 py-20 md:py-28 bg-slate-950 text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8 mb-14">
            <div class="max-w-xl">
                <p class="text-xs font-bold uppercase tracking-widest text-cyan-300/90 mb-3">The registration story you sell</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">A client-ready journey from first call to filed entity</h2>
            </div>
            <p class="text-slate-400 max-w-md text-sm leading-relaxed lg:text-right">
                Turn opaque government steps into a branded experience your prospects can see before they sign—ideal for consultancies, agencies, and corporate services firms.
            </p>
        </div>

        <ol class="grid gap-6 md:grid-cols-2 lg:grid-cols-4 relative">
            <li class="hidden lg:block absolute top-10 left-[12%] right-[12%] h-px bg-gradient-to-r from-transparent via-cyan-500/40 to-transparent pointer-events-none" aria-hidden="true"></li>
            @foreach([
                ['Discovery &amp; scope','Capture entity type, trade name options, and shareholder structure in a single intake.','01','cyan'],
                ['Compliance pack','Collect IDs, proof of address, and statutory forms with versioned uploads.','02','blue'],
                ['Registrar workflow','Track fees, references, and filing status with internal notes your team trusts.','03','indigo'],
                ['Go-live handoff','Auto-create client records, domain placeholders, and first-year renewal calendar.','04','violet'],
            ] as $step)
            <li class="relative rounded-2xl border border-white/10 bg-white/[0.03] p-6 backdrop-blur-sm">
                <span class="text-[11px] font-extrabold uppercase tracking-widest
                    @if($step[3]==='cyan') text-cyan-300
                    @elseif($step[3]==='blue') text-blue-300
                    @elseif($step[3]==='indigo') text-indigo-300
                    @else text-violet-300 @endif">{{ $step[2] }}</span>
                <h3 class="mt-3 text-lg font-bold">{{ $step[0] }}</h3>
                <p class="mt-2 text-sm text-slate-400 leading-relaxed">{!! $step[1] !!}</p>
            </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- ALERTS SHOWCASE --}}
<section id="alerts" class="scroll-mt-24 py-20 md:py-28 bg-white border-y border-slate-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <p class="text-xs font-bold uppercase tracking-widest text-violet-600 mb-3">Proactive, not reactive</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Alerts that read like a partner briefing</h2>
            <p class="mt-3 text-slate-600">Every notification ties to revenue, risk, or reputation—so your team knows what to do first.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach([
                ['Domain','Lead time','.co.zw renewal','acmecorp.co.zw','32 days','Registrar auto-renew is OFF. Draft the client email and prep the renewal cart.','border-cyan-200 bg-cyan-50/80','text-cyan-800','bg-cyan-600'],
                ['Email','Billing','Workspace renewal','Microsoft 365 · 12 seats','18 days','Annual commit refreshes on the 14th. Confirm seat count with IT before finance approves.','border-violet-200 bg-violet-50/80','text-violet-900','bg-violet-600'],
                ['Vault','Risk','Tax clearance','ZIMRA · 2025','24 days','Attach the new scan after client drop-off. Compliance holds active quotes until this is green.','border-amber-200 bg-amber-50/80','text-amber-900','bg-amber-500'],
            ] as $card)
            <div class="rounded-2xl border-2 {{ $card[6] }} p-5 flex flex-col shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider {{ $card[7] }}">{{ $card[0] }}</span>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-white/80 text-slate-700 border border-slate-200">{{ $card[1] }}</span>
                </div>
                <p class="mt-3 text-lg font-bold text-slate-900">{{ $card[2] }}</p>
                <p class="text-sm text-slate-600">{{ $card[3] }}</p>
                <p class="mt-4 text-3xl font-extrabold tabular-nums text-slate-900">{{ $card[4] }}</p>
                <p class="mt-3 text-sm text-slate-600 leading-relaxed flex-1">{{ $card[5] }}</p>
                <div class="mt-4 h-1 rounded-full bg-slate-200/80 overflow-hidden">
                    <div class="h-full w-2/3 rounded-full {{ $card[8] }}"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PLATFORM --}}
<section id="features" class="scroll-mt-24 py-20 md:py-28 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-14 md:mb-16">
            <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-3">Full platform</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Everything around the compliance core</h2>
            <p class="mt-3 text-slate-600">When dates are under control, your team can focus on clients, pipeline, and cashflow—the rest of DealFlow Pro is built for that motion.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-6">
            @php
            $features = [
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                    'color' => 'blue',
                    'title' => 'Orders &amp; service packs',
                    'desc' => 'Sell registration bundles, domain packs, and retainers as structured orders—linked to documents and renewal tasks automatically.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
                    'color' => 'cyan',
                    'title' => 'Clients &amp; stakeholders',
                    'desc' => 'Beneficial owners, billing contacts, and approvers stay attached to every obligation—so alerts always reach the right inbox.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10m0-10a2 2 0 012 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>',
                    'color' => 'violet',
                    'title' => 'Pipeline &amp; deals',
                    'desc' => 'Move opportunities from first meeting to won—without losing sight of statutory deadlines tied to each deal.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'color' => 'emerald',
                    'title' => 'Quotes, invoices &amp; cashflow',
                    'desc' => 'Professional quotes that convert, invoices that get paid, and cashflow views that explain what is coming—not just what happened.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
                    'color' => 'amber',
                    'title' => 'Smart reminders',
                    'desc' => 'Digest emails and in-app queues prioritise what is about to slip—domains, seats, licences, and payment milestones in one stream.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>',
                    'color' => 'indigo',
                    'title' => 'Command dashboard',
                    'desc' => 'A single morning view: renewals this week, documents ageing out, and revenue at risk—tuned for owners and operations leads.',
                ],
            ];
            $colorMap = [
                'blue' => ['bg' => 'bg-blue-50', 'icon' => 'text-blue-600', 'ring' => 'ring-blue-100'],
                'cyan' => ['bg' => 'bg-cyan-50', 'icon' => 'text-cyan-600', 'ring' => 'ring-cyan-100'],
                'violet' => ['bg' => 'bg-violet-50', 'icon' => 'text-violet-600', 'ring' => 'ring-violet-100'],
                'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-600', 'ring' => 'ring-emerald-100'],
                'amber' => ['bg' => 'bg-amber-50', 'icon' => 'text-amber-600', 'ring' => 'ring-amber-100'],
                'indigo' => ['bg' => 'bg-indigo-50', 'icon' => 'text-indigo-600', 'ring' => 'ring-indigo-100'],
            ];
            @endphp

            @foreach($features as $feature)
            @php $c = $colorMap[$feature['color']]; @endphp
            <div class="group rounded-2xl border border-slate-200 bg-white p-6 md:p-7 shadow-sm hover:shadow-md hover:border-slate-300/80 transition-all duration-300">
                <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl {{ $c['bg'] }} ring-1 {{ $c['ring'] }} transition-transform duration-300 group-hover:scale-105">
                    <svg class="h-5 w-5 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">{!! $feature['icon'] !!}</svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">{!! $feature['title'] !!}</h3>
                <p class="mt-2 text-sm text-slate-600 leading-relaxed">{!! $feature['desc'] !!}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- QUICK START --}}
<section class="py-20 md:py-24 bg-slate-950 text-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-12 md:mb-14">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Live in one afternoon</h2>
            <p class="mt-3 text-slate-400 max-w-lg mx-auto text-sm md:text-base">No heavyweight implementation—bring your client list, turn on renewals, and let the first alerts prove the value.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8 md:gap-10 relative">
            <div class="hidden md:block absolute top-8 left-[20%] right-[20%] h-px bg-gradient-to-r from-transparent via-cyan-500/30 to-transparent pointer-events-none" aria-hidden="true"></div>
            @foreach([
                ['01','Create your workspace','Sign up, invite your team, and pick the compliance modules you sell today.'],
                ['02','Import clients &amp; dates','Add domains, subscription renewals, and document expiries—or start fresh with our templates.'],
                ['03','Share the story','Use this landing narrative in proposals: one system from registration to renewal.'],
            ] as $step)
            <div class="relative text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 text-lg font-extrabold text-white shadow-lg shadow-cyan-500/25">{{ $step[0] }}</div>
                <h3 class="mt-5 text-lg font-bold">{{ $step[1] }}</h3>
                <p class="mt-2 text-sm text-slate-400 max-w-xs mx-auto leading-relaxed">{!! $step[2] !!}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PRICING --}}
<section id="pricing" class="scroll-mt-24 py-20 md:py-28 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center max-w-2xl mx-auto mb-12 md:mb-14">
            <p class="text-xs font-bold uppercase tracking-widest text-emerald-600 mb-3">Pricing</p>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Start free. Scale when renewals deserve automation.</h2>
            <p class="mt-3 text-slate-600">Pro unlocks advanced renewal rules, team seats, and the alert depth growing consultancies need.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto">
            <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-8">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Free</p>
                <div class="mt-3 flex items-baseline gap-1">
                    <span class="text-5xl font-extrabold text-slate-900">$0</span>
                    <span class="text-slate-500">/mo</span>
                </div>
                <p class="mt-3 text-sm text-slate-600">Prove the workflow on your first cohort of clients.</p>
                <a href="/register" class="mt-7 block w-full rounded-xl border-2 border-slate-200 py-3 text-center text-sm font-bold text-slate-800 hover:border-slate-300 hover:bg-white transition-colors">Get started free</a>
                <ul class="mt-8 flex flex-col gap-2.5 text-sm text-slate-600">
                    @foreach(['Registration intake &amp; documents','Domain &amp; expiry reminders (basic)','Up to 50 clients','Quotes &amp; invoices','Community support'] as $item)
                    <li class="flex gap-2">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {!! $item !!}
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative overflow-hidden rounded-2xl border border-blue-500/30 bg-slate-950 p-8 text-white shadow-xl shadow-blue-950/40">
                <div class="pointer-events-none absolute -right-20 -top-20 h-56 w-56 rounded-full bg-blue-600/20 blur-3xl"></div>
                <div class="relative">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-cyan-300">Pro</p>
                        <span class="rounded-full bg-cyan-400/20 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-cyan-200 border border-cyan-400/30">Most popular</span>
                    </div>
                    <div class="mt-3 flex items-baseline gap-1">
                        <span class="text-5xl font-extrabold">$2</span>
                        <span class="text-slate-400">/mo</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Billed annually ($24/yr)</p>
                    <p class="mt-2 text-sm text-slate-400">Renewal escalations, deeper alerts, and room to grow.</p>
                    <a href="/register" class="mt-7 block w-full rounded-xl bg-gradient-to-r from-cyan-400 to-blue-500 py-3 text-center text-sm font-extrabold text-slate-950 shadow-lg shadow-cyan-500/20 hover:brightness-105 transition-all">Start 14-day trial</a>
                    <ul class="mt-8 flex flex-col gap-2.5 text-sm text-slate-300">
                        @foreach(['Everything in Free','Multi-step renewal rules (60/30/7)','Email subscription tracking','Team members','Advanced reporting','Priority support'] as $item)
                        <li class="flex gap-2">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-cyan-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="relative overflow-hidden py-20 md:py-28 bg-gradient-to-br from-blue-700 via-blue-800 to-slate-950">
    <div class="pointer-events-none absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_30%_20%,rgba(34,211,238,0.35),transparent_50%)]"></div>
    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 text-center">
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-tight">Sell the process. Ship the peace of mind.</h2>
        <p class="mt-5 text-base sm:text-lg text-blue-100/90">DealFlow Pro is the page your prospects remember—then the product your team relies on Monday morning.</p>
        <div class="mt-10 flex flex-col sm:flex-row justify-center gap-3">
            <a href="/register" class="inline-flex justify-center items-center px-8 py-3.5 rounded-xl bg-white text-blue-900 font-extrabold shadow-xl hover:bg-slate-50 transition-colors">Create free account</a>
            <a href="/admin/login" class="inline-flex justify-center items-center px-8 py-3.5 rounded-xl border border-white/25 bg-white/5 text-white font-semibold hover:bg-white/10 transition-colors">Explore demo</a>
        </div>
        <p class="mt-5 text-sm text-blue-200/80">No credit card on the free tier.</p>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-[#030712] text-slate-500">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 md:py-14">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-10">
            <div class="max-w-sm">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600">
                        <svg class="h-[18px] w-[18px] text-white" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M2 4a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4z"/>
                            <path fill-rule="evenodd" d="M2 9.5A.5.5 0 012.5 9h15a.5.5 0 010 1h-15A.5.5 0 012 9.5zm0 3A.5.5 0 012.5 12h15a.5.5 0 010 1h-15A.5.5 0 012 12zm0 3A.5.5 0 012.5 15h10a.5.5 0 010 1h-10A.5.5 0 012 15z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="leading-none">
                        <span class="block text-base font-extrabold text-white">DealFlow</span>
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-cyan-400/90">Pro</span>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-relaxed">Registration through renewal—alerts, documents, and revenue in one place for consultancies and SMEs.</p>
            </div>
            <div class="flex flex-wrap gap-12 md:gap-16">
                <div>
                    <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-3">Product</h4>
                    <ul class="flex flex-col gap-2 text-sm">
                        <li><a href="#process" class="hover:text-white transition-colors">Registration</a></li>
                        <li><a href="#renewals" class="hover:text-white transition-colors">Renewals</a></li>
                        <li><a href="#alerts" class="hover:text-white transition-colors">Alerts</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Platform</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[11px] font-bold uppercase tracking-wider text-slate-600 mb-3">Account</h4>
                    <ul class="flex flex-col gap-2 text-sm">
                        <li><a href="/admin/login" class="hover:text-white transition-colors">Sign in</a></li>
                        <li><a href="/register" class="hover:text-white transition-colors">Register</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mt-12 flex flex-col sm:flex-row justify-between items-center gap-3 border-t border-white/5 pt-8 text-xs">
            <p class="text-slate-600">&copy; {{ date('Y') }} DealFlow Pro. All rights reserved.</p>
            <p class="text-slate-600">Built by <a href="https://softurf.co.zw" class="text-slate-400 hover:text-white transition-colors">Softurf Solutions</a></p>
        </div>
    </div>
</footer>

</body>
</html>