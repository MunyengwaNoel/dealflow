<div class="mt-6 rounded-xl border border-slate-500/40 bg-slate-900/90 p-4 ring-1 ring-white/10">
    <div class="mb-3 flex flex-wrap items-center gap-2">
        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-500/15 px-2 py-0.5 text-xs font-bold text-emerald-200">
            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-400" aria-hidden="true"></span>
            {{ __('Live demo') }}
        </span>
        <span class="text-xs text-slate-400">{{ __('Try it now — no sign-up needed') }}</span>
    </div>
    <dl class="flex flex-col gap-2.5 text-sm">
        <div class="flex items-center justify-between gap-3">
            <dt class="text-xs font-medium text-slate-400">{{ __('Email') }}</dt>
            <dd class="font-mono text-xs font-semibold text-sky-300">demo@dealflow.app</dd>
        </div>
        <div class="flex items-center justify-between gap-3">
            <dt class="text-xs font-medium text-slate-400">{{ __('Password') }}</dt>
            <dd class="font-mono text-xs font-semibold text-slate-100">password</dd>
        </div>
    </dl>
    <p class="mt-3 text-xs leading-relaxed text-slate-400">
        {{ __('Pre-loaded with sample clients, deals, and invoices. Destructive actions are disabled.') }}
    </p>
</div>
