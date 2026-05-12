@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge([
            'class' => 'block w-full rounded-xl border border-slate-200 bg-white py-2 px-3.5 text-sm text-slate-900 shadow-sm transition-colors placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/15 disabled:cursor-not-allowed disabled:opacity-50',
]) }}>
