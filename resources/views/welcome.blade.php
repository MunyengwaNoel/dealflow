<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BizTrack — Manage. Track. Grow.</title>
    <meta name="description" content="The all-in-one business management platform for freelancers and small businesses. Track clients, close deals, send invoices, and grow revenue.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-nunito antialiased bg-white text-slate-900">

{{-- ============================================================ --}}
{{-- NAVIGATION --}}
{{-- ============================================================ --}}
<header x-data="{ scrolled: false, mobileOpen: false }"
        @scroll.window="scrolled = window.scrollY > 20"
        :class="scrolled ? 'bg-white/95 backdrop-blur shadow-sm' : 'bg-transparent'"
        class="fixed inset-x-0 top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-5 sm:px-8">
        <div class="flex items-center justify-between h-16 md:h-18">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-4.5 h-4.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 4a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4z"/>
                        <path fill-rule="evenodd" d="M2 9.5A.5.5 0 012.5 9h15a.5.5 0 010 1h-15A.5.5 0 012 9.5zm0 3A.5.5 0 012.5 12h15a.5.5 0 010 1h-15A.5.5 0 012 12zm0 3A.5.5 0 012.5 15h10a.5.5 0 010 1h-10A.5.5 0 012 15z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xl font-black tracking-tight" :class="scrolled ? 'text-slate-900' : 'text-white'">BizTrack</span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1">
                <a href="#features" :class="scrolled ? 'text-slate-600 hover:text-slate-900' : 'text-white/80 hover:text-white'" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors">Features</a>
                <a href="#pricing" :class="scrolled ? 'text-slate-600 hover:text-slate-900' : 'text-white/80 hover:text-white'" class="px-4 py-2 text-sm font-medium rounded-lg transition-colors">Pricing</a>
            </nav>

            {{-- Desktop CTAs --}}
            <div class="hidden md:flex items-center gap-3">
                <a href="/admin/login"
                   :class="scrolled ? 'text-slate-600 hover:text-slate-900' : 'text-white/80 hover:text-white'"
                   class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors">
                    Sign In
                </a>
                <a href="/admin/login"
                   class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-blue-600/30 transition-all duration-200 hover:scale-105 hover:shadow-blue-500/40">
                    Start Free
                </a>
            </div>

            {{-- Mobile menu button --}}
            <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden p-2 rounded-lg"
                    :class="scrolled ? 'text-slate-700' : 'text-white'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileOpen" x-transition class="md:hidden pb-4 border-t border-white/10 mt-2">
            <div class="flex flex-col gap-1 pt-3">
                <a href="#features" @click="mobileOpen=false" class="px-4 py-2.5 text-sm font-medium text-white/80 rounded-lg">Features</a>
                <a href="#pricing" @click="mobileOpen=false" class="px-4 py-2.5 text-sm font-medium text-white/80 rounded-lg">Pricing</a>
                <div class="flex gap-3 mt-3 pt-3 border-t border-white/10">
                    <a href="/admin/login" class="flex-1 text-center py-2.5 text-sm font-semibold text-white/80 border border-white/20 rounded-xl">Sign In</a>
                    <a href="/admin/login" class="flex-1 text-center py-2.5 text-sm font-semibold bg-blue-600 text-white rounded-xl">Start Free</a>
                </div>
            </div>
        </div>
    </div>
</header>


{{-- ============================================================ --}}
{{-- HERO --}}
{{-- ============================================================ --}}
<section class="relative min-h-screen bg-[#0A0F1E] overflow-hidden flex flex-col">

    {{-- Background grid --}}
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDBoNjB2NjBIMHoiLz48cGF0aCBkPSJNNjAgMEgwdjYwaDYwVjB6TTEgMWg1OHY1OEgxVjF6IiBmaWxsPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMDMpIi8+PC9nPjwvc3ZnPg==')] opacity-40"></div>

    {{-- Gradient orbs --}}
    <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-blue-700/20 rounded-full blur-3xl -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-indigo-700/15 rounded-full blur-3xl translate-y-1/2"></div>

    <div class="relative flex-1 flex flex-col max-w-7xl mx-auto px-5 sm:px-8 pt-32 pb-16">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-8 flex-1">

            {{-- Left: copy --}}
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold uppercase tracking-wider mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                    Business management, simplified
                </div>

                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight">
                    Manage.<br>
                    Track.<br>
                    <span class="text-transparent [-webkit-text-fill-color:transparent] [background-clip:text] [-webkit-background-clip:text] bg-gradient-to-r from-blue-400 to-indigo-400">Grow.</span>
                </h1>

                <p class="mt-6 text-lg text-slate-400 leading-relaxed max-w-lg lg:max-w-none">
                    The all-in-one platform for freelancers and small businesses.
                    Track clients, close deals, send invoices, and stay on top of every document.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row items-center lg:justify-start justify-center gap-3">
                    <a href="/register"
                       class="w-full sm:w-auto px-7 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-xl shadow-blue-600/40 transition-all duration-200 hover:scale-105 hover:shadow-blue-500/50 text-center">
                        Start for free
                    </a>
                    <a href="/admin/login"
                       class="w-full sm:w-auto px-7 py-3.5 bg-white/8 hover:bg-white/12 border border-white/10 text-white font-semibold rounded-xl transition-all duration-200 text-center backdrop-blur-sm">
                        View demo
                        <span class="ml-1.5 text-slate-400">→</span>
                    </a>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row items-center lg:justify-start justify-center gap-5 text-sm text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Free forever plan
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        No credit card needed
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Set up in minutes
                    </span>
                </div>
            </div>

            {{-- Right: app mockup --}}
            <div class="flex-1 w-full max-w-2xl lg:max-w-none">
                <div class="relative">
                    {{-- Glow behind mockup --}}
                    <div class="absolute inset-0 bg-blue-600/20 rounded-2xl blur-2xl scale-95"></div>

                    {{-- Browser chrome + app mockup --}}
                    <div class="relative rounded-2xl overflow-hidden border border-white/10 shadow-2xl shadow-black/60 bg-slate-900">
                        {{-- Browser bar --}}
                        <div class="flex items-center gap-2 px-4 py-3 bg-slate-800 border-b border-white/5">
                            <div class="flex gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-red-500/70"></span>
                                <span class="w-3 h-3 rounded-full bg-yellow-500/70"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-500/70"></span>
                            </div>
                            <div class="flex-1 mx-4">
                                <div class="bg-slate-700/50 rounded-md px-3 py-1 text-xs text-slate-400 text-center">biztrack.app/admin</div>
                            </div>
                        </div>

                        {{-- App layout --}}
                        <div class="flex" style="height: 380px;">
                            {{-- Sidebar --}}
                            <div class="w-44 bg-white border-r border-slate-100 flex flex-col shrink-0">
                                <div class="flex items-center gap-2 p-3 border-b border-slate-100">
                                    <div class="w-6 h-6 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-700"></div>
                                    <span class="text-xs font-black text-slate-900">BizTrack</span>
                                </div>
                                <nav class="p-2 flex flex-col gap-0.5 flex-1">
                                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold">
                                        <div class="w-3.5 h-3.5 bg-blue-600 rounded opacity-80"></div>
                                        Dashboard
                                    </div>
                                    @foreach([['Clients','slate'], ['Deals','slate'], ['Invoices','slate'], ['Quotes','slate'], ['Documents','slate'], ['Cashflow','slate']] as $item)
                                    <div class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-slate-500 text-xs">
                                        <div class="w-3.5 h-3.5 bg-slate-300 rounded opacity-70"></div>
                                        {{ $item[0] }}
                                    </div>
                                    @endforeach
                                </nav>
                                <div class="p-2 border-t border-slate-100">
                                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-2">
                                        <div class="text-xs font-bold text-blue-700 mb-0.5">Free Plan</div>
                                        <div class="h-1.5 bg-slate-200 rounded-full">
                                            <div class="h-full w-1/3 bg-blue-500 rounded-full"></div>
                                        </div>
                                        <div class="text-[10px] text-slate-500 mt-1">12/50 clients</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Main --}}
                            <div class="flex-1 bg-slate-50 overflow-hidden">
                                {{-- Topbar --}}
                                <div class="flex items-center justify-between px-4 py-2.5 bg-white border-b border-slate-100">
                                    <div>
                                        <div class="text-xs font-bold text-slate-900">Welcome back, John 👋</div>
                                        <div class="text-[10px] text-slate-400">Here's what's happening today.</div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-full bg-red-500 flex items-center justify-center text-white text-[8px] font-bold">3</div>
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600"></div>
                                    </div>
                                </div>

                                {{-- Stats --}}
                                <div class="grid grid-cols-4 gap-2 p-3">
                                    @foreach([
                                        ['128','Customers','emerald'],
                                        ['45','Active Orders','blue'],
                                        ['23','Open Deals','violet'],
                                        ['$24,560','Revenue','amber'],
                                    ] as $stat)
                                    <div class="bg-white rounded-xl p-2.5 border border-slate-100">
                                        <div class="text-base font-black text-slate-900">{{ $stat[0] }}</div>
                                        <div class="text-[9px] text-slate-400">{{ $stat[1] }}</div>
                                        <div class="flex items-center gap-0.5 mt-1">
                                            <svg class="w-2.5 h-2.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                            <span class="text-[9px] text-emerald-600 font-semibold">12%</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                {{-- Charts row --}}
                                <div class="grid grid-cols-2 gap-2 px-3 pb-2">
                                    {{-- Deal pipeline mini --}}
                                    <div class="bg-white rounded-xl border border-slate-100 p-2.5">
                                        <div class="text-[10px] font-bold text-slate-700 mb-2">Deals Pipeline</div>
                                        <div class="flex gap-1 h-16">
                                            @foreach([
                                                ['New','bg-slate-200',40],
                                                ['Qualified','bg-blue-200',65],
                                                ['Proposal','bg-indigo-300',50],
                                                ['Won','bg-emerald-400',80],
                                            ] as $stage)
                                            <div class="flex-1 flex flex-col justify-end gap-0.5">
                                                <div class="{{ $stage[1] }} rounded-sm" style="height: {{ $stage[2] }}%;"></div>
                                                <div class="text-[8px] text-slate-400 text-center truncate">{{ $stage[0] }}</div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    {{-- Recent orders mini --}}
                                    <div class="bg-white rounded-xl border border-slate-100 p-2.5">
                                        <div class="text-[10px] font-bold text-slate-700 mb-2">Recent Orders</div>
                                        <div class="flex flex-col gap-1.5">
                                            @foreach([
                                                ['ABC Tech','$2,450','bg-emerald-100 text-emerald-700','Paid'],
                                                ['BrightSol','$3,200','bg-amber-100 text-amber-700','Pending'],
                                                ['CloudPeak','$7,500','bg-emerald-100 text-emerald-700','Paid'],
                                            ] as $order)
                                            <div class="flex items-center justify-between">
                                                <span class="text-[9px] text-slate-600">{{ $order[0] }}</span>
                                                <div class="flex items-center gap-1">
                                                    <span class="text-[9px] font-semibold text-slate-700">{{ $order[1] }}</span>
                                                    <span class="text-[8px] px-1 py-0.5 rounded {{ $order[2] }} font-medium">{{ $order[3] }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Floating alert card --}}
                    <div class="absolute -right-4 top-16 bg-white rounded-xl shadow-xl border border-slate-100 p-3 w-52 hidden lg:block">
                        <div class="flex items-start gap-2">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-800">3 docs expiring</p>
                                <p class="text-xs text-slate-400 mt-0.5">Tax clearances due in 30 days</p>
                            </div>
                        </div>
                    </div>

                    {{-- Floating stats card --}}
                    <div class="absolute -left-4 bottom-12 bg-white rounded-xl shadow-xl border border-slate-100 p-3 w-48 hidden lg:block">
                        <p class="text-xs text-slate-400">Revenue this month</p>
                        <p class="text-xl font-black text-slate-900 mt-0.5">$24,560</p>
                        <div class="flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                            <span class="text-xs text-emerald-600 font-semibold">18% this month</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Scroll hint --}}
        <div class="flex justify-center mt-10 lg:mt-6">
            <a href="#features" class="flex flex-col items-center gap-1.5 text-slate-600 hover:text-slate-400 transition-colors animate-bounce">
                <span class="text-xs uppercase tracking-widest">Explore</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </a>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SOCIAL PROOF / TRUST BAR --}}
{{-- ============================================================ --}}
<section class="border-y border-slate-100 bg-slate-50/50 py-8">
    <div class="max-w-7xl mx-auto px-5 sm:px-8">
        <div class="flex flex-wrap justify-center items-center gap-6 sm:gap-12 text-center">
            @foreach([
                ['500+','Businesses'],
                ['12,000+','Clients tracked'],
                ['$2M+','Invoiced'],
                ['4.9/5','User rating'],
            ] as $proof)
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $proof[0] }}</div>
                <div class="text-xs text-slate-500 mt-0.5 uppercase tracking-wider">{{ $proof[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- FEATURES --}}
{{-- ============================================================ --}}
<section id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-5 sm:px-8">

        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold uppercase tracking-wider mb-4">
                Everything in one place
            </div>
            <h2 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                Built for how you work
            </h2>
            <p class="mt-4 text-lg text-slate-500 max-w-2xl mx-auto">
                Every tool you need to run your business, without the bloat. No training required.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @php
            $features = [
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
                    'color' => 'blue',
                    'title' => 'Client Management',
                    'desc' => 'Centralise every client contact, deal history, and document in one searchable place. Never lose track of a relationship again.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10m0-10a2 2 0 012 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>',
                    'color' => 'violet',
                    'title' => 'Deals Pipeline',
                    'desc' => 'Visualise your sales pipeline on a drag-and-drop Kanban board. Move deals from qualified to won with a single gesture.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
                    'color' => 'emerald',
                    'title' => 'Invoices & Quotes',
                    'desc' => 'Create professional quotes in seconds, convert them to invoices with one click, and track every payment automatically.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>',
                    'color' => 'amber',
                    'title' => 'Document Vault',
                    'desc' => 'Store tax clearances, business licenses, ID documents, and more. Get alerted before anything expires so you never miss a deadline.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>',
                    'color' => 'rose',
                    'title' => 'Cashflow Tracking',
                    'desc' => 'Monitor income and expenses over time. Spot trends early, understand your margins, and make confident financial decisions.',
                ],
                [
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
                    'color' => 'indigo',
                    'title' => 'Smart Alerts',
                    'desc' => 'Automated reminders for expiring documents, pending payments, and upcoming deal follow-ups. Stay proactive, not reactive.',
                ],
            ];
            @endphp

            @foreach($features as $feature)
            @php
            $colorMap = [
                'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-600',   'ring' => 'ring-blue-100'],
                'violet' => ['bg' => 'bg-violet-50', 'icon' => 'text-violet-600', 'ring' => 'ring-violet-100'],
                'emerald'=> ['bg' => 'bg-emerald-50','icon' => 'text-emerald-600','ring' => 'ring-emerald-100'],
                'amber'  => ['bg' => 'bg-amber-50',  'icon' => 'text-amber-600',  'ring' => 'ring-amber-100'],
                'rose'   => ['bg' => 'bg-rose-50',   'icon' => 'text-rose-600',   'ring' => 'ring-rose-100'],
                'indigo' => ['bg' => 'bg-indigo-50', 'icon' => 'text-indigo-600', 'ring' => 'ring-indigo-100'],
            ];
            $c = $colorMap[$feature['color']];
            @endphp
            <div class="group p-7 rounded-2xl border border-slate-100 hover:border-slate-200 hover:shadow-lg hover:shadow-slate-100 transition-all duration-300">
                <div class="w-11 h-11 rounded-xl {{ $c['bg'] }} ring-1 {{ $c['ring'] }} flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $feature['icon'] !!}
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 mb-2">{{ $feature['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $feature['desc'] }}</p>
            </div>
            @endforeach

        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- HOW IT WORKS --}}
{{-- ============================================================ --}}
<section class="py-24 bg-slate-950">
    <div class="max-w-7xl mx-auto px-5 sm:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl sm:text-5xl font-black text-white tracking-tight">Up and running in minutes</h2>
            <p class="mt-4 text-slate-400 max-w-xl mx-auto">No onboarding calls. No setup fees. Just sign up and start managing your business.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            {{-- Connector line --}}
            <div class="hidden md:block absolute top-8 left-1/3 right-1/3 h-0.5 bg-gradient-to-r from-blue-600/0 via-blue-600/50 to-blue-600/0"></div>

            @foreach([
                ['01', 'Create your account', 'Sign up with your email in under 30 seconds. Your workspace is ready immediately.', 'blue'],
                ['02', 'Add your clients & deals', 'Import existing contacts or add them one by one. Structure your pipeline the way you think.', 'indigo'],
                ['03', 'Invoice and get paid', 'Send professional quotes and invoices. Track payments and watch your revenue grow.', 'violet'],
            ] as $i => $step)
            <div class="relative text-center">
                <div class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center text-2xl font-black
                    @if($i === 0) bg-blue-600 text-white @elseif($i === 1) bg-indigo-600 text-white @else bg-violet-600 text-white @endif
                    shadow-xl @if($i === 0) shadow-blue-500/40 @elseif($i === 1) shadow-indigo-500/40 @else shadow-violet-500/40 @endif
                    ">
                    {{ $step[0] }}
                </div>
                <h3 class="mt-5 text-lg font-bold text-white">{{ $step[1] }}</h3>
                <p class="mt-2 text-sm text-slate-400 max-w-xs mx-auto leading-relaxed">{{ $step[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- PRICING --}}
{{-- ============================================================ --}}
<section id="pricing" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-5 sm:px-8">

        <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-semibold uppercase tracking-wider mb-4">
                Simple, transparent pricing
            </div>
            <h2 class="text-4xl sm:text-5xl font-black text-slate-900 tracking-tight">Start free. Scale when ready.</h2>
            <p class="mt-4 text-slate-500 max-w-xl mx-auto">No hidden fees. Cancel anytime. Upgrade only when your business needs it.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl mx-auto">

            {{-- Free plan --}}
            <div class="rounded-2xl border border-slate-200 p-8">
                <div class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Free Plan</div>
                <div class="mt-4 flex items-baseline gap-1">
                    <span class="text-5xl font-black text-slate-900">$0</span>
                    <span class="text-slate-400">/month</span>
                </div>
                <p class="mt-3 text-sm text-slate-500">For getting started and exploring what BizTrack can do.</p>

                <a href="/register"
                   class="mt-7 block w-full text-center py-3 rounded-xl border-2 border-slate-200 text-slate-700 font-semibold hover:border-slate-300 hover:bg-slate-50 transition-all duration-200">
                    Get started free
                </a>

                <ul class="mt-8 flex flex-col gap-3">
                    @foreach(['Up to 50 clients','Up to 100 documents','5 deals per month','Basic alerts','Standard invoice templates','Community support'] as $item)
                    <li class="flex items-center gap-2.5 text-sm text-slate-600">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Pro plan --}}
            <div class="relative rounded-2xl bg-slate-950 p-8 overflow-hidden">
                {{-- Popular badge --}}
                <div class="absolute top-5 right-5 px-2.5 py-1 rounded-full bg-blue-600 text-white text-xs font-bold uppercase tracking-wider">
                    Most popular
                </div>

                {{-- Subtle glow --}}
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl -translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

                <div class="relative">
                    <div class="text-sm font-semibold text-blue-400 uppercase tracking-wider">Pro Plan</div>
                    <div class="mt-4 flex items-baseline gap-1">
                        <span class="text-5xl font-black text-white">$2</span>
                        <span class="text-slate-400">/month</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Billed annually ($24/yr)</p>
                    <p class="mt-2 text-sm text-slate-400">Everything in Free, plus unlimited growth.</p>

                    <a href="/register"
                       class="mt-7 block w-full text-center py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold shadow-lg shadow-blue-600/30 transition-all duration-200 hover:scale-[1.02]">
                        Start 14-day free trial
                    </a>

                    <ul class="mt-8 flex flex-col gap-3">
                        @foreach(['Unlimited clients','Unlimited documents','Unlimited deals','Advanced alerts & reminders','Custom invoice templates','PDF exports','Priority support','Team members','Advanced reports & insights'] as $item)
                        <li class="flex items-center gap-2.5 text-sm text-slate-300">
                            <svg class="w-4 h-4 text-blue-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- FINAL CTA --}}
{{-- ============================================================ --}}
<section class="py-24 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 overflow-hidden relative">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-indigo-400/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

    <div class="relative max-w-3xl mx-auto px-5 sm:px-8 text-center">
        <h2 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight">
            Ready to take control of your business?
        </h2>
        <p class="mt-5 text-lg text-blue-100">
            Join hundreds of businesses already using BizTrack to close more deals, get paid faster, and grow with confidence.
        </p>
        <div class="mt-10 flex flex-col sm:flex-row justify-center gap-3">
            <a href="/register"
               class="px-8 py-4 bg-white hover:bg-slate-50 text-blue-700 font-bold rounded-xl shadow-xl transition-all duration-200 hover:scale-105">
                Start your free account
            </a>
            <a href="/admin/login"
               class="px-8 py-4 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold rounded-xl transition-all duration-200">
                Explore the demo →
            </a>
        </div>
        <p class="mt-5 text-sm text-blue-200">No credit card required. Free plan available forever.</p>
    </div>
</section>


{{-- ============================================================ --}}
{{-- FOOTER --}}
{{-- ============================================================ --}}
<footer class="bg-slate-950 text-slate-400">
    <div class="max-w-7xl mx-auto px-5 sm:px-8 py-12">
        <div class="flex flex-col md:flex-row justify-between items-start gap-8">

            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 4a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4z"/>
                            <path fill-rule="evenodd" d="M2 9.5A.5.5 0 012.5 9h15a.5.5 0 010 1h-15A.5.5 0 012 9.5zm0 3A.5.5 0 012.5 12h15a.5.5 0 010 1h-15A.5.5 0 012 12zm0 3A.5.5 0 012.5 15h10a.5.5 0 010 1h-10A.5.5 0 012 15z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-lg font-black text-white">BizTrack</span>
                </div>
                <p class="text-sm leading-relaxed max-w-xs">
                    The all-in-one business management platform built for modern freelancers and SMEs.
                </p>
            </div>

            {{-- Links --}}
            <div class="flex flex-wrap gap-12">
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Product</h4>
                    <ul class="flex flex-col gap-2 text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="/admin/login" class="hover:text-white transition-colors">Live demo</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Account</h4>
                    <ul class="flex flex-col gap-2 text-sm">
                        <li><a href="/admin/login" class="hover:text-white transition-colors">Sign in</a></li>
                        <li><a href="/register" class="hover:text-white transition-colors">Register</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="mt-10 pt-8 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-3">
            <p class="text-xs text-slate-600">&copy; {{ date('Y') }} BizTrack. All rights reserved.</p>
            <p class="text-xs text-slate-600">Built by <a href="https://softurf.co.zw" class="hover:text-white transition-colors">Softurf Solutions</a></p>
        </div>
    </div>
</footer>

</body>
</html>
