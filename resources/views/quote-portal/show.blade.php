@extends('layouts.guest')

@section('content')
    <div class="w-full max-w-lg rounded-2xl border border-white/10 bg-white/5 p-8 shadow-2xl backdrop-blur">
        <p class="text-xs font-semibold uppercase tracking-widest text-blue-300">DealFlow Pro · Client quote</p>
        <h1 class="mt-2 text-2xl font-bold text-white">Quote {{ $quote->quote_number }}</h1>
        <p class="mt-1 text-sm text-slate-300">{{ $quote->tenant?->name }}</p>

        @if (session('status'))
            <div class="mt-4 rounded-lg bg-emerald-500/15 px-3 py-2 text-sm text-emerald-200">{{ session('status') }}</div>
        @endif

        <dl class="mt-6 space-y-2 text-sm text-slate-200">
            <div class="flex justify-between gap-4"><dt>Client</dt><dd class="text-right font-medium text-white">{{ $quote->client?->name }}</dd></div>
            <div class="flex justify-between gap-4"><dt>Total</dt><dd class="text-right font-semibold text-white">${{ number_format((float) $quote->total, 2) }}</dd></div>
            @if ($quote->valid_until)
                <div class="flex justify-between gap-4"><dt>Valid until</dt><dd class="text-right">{{ $quote->valid_until->toFormattedDateString() }}</dd></div>
            @endif
        </dl>

        <div class="mt-6 border-t border-white/10 pt-6">
            <p class="text-sm font-semibold text-white">Line items</p>
            <ul class="mt-3 space-y-2 text-sm text-slate-300">
                @foreach ($quote->items as $item)
                    <li class="flex justify-between gap-2">
                        <span>{{ $item->name }} × {{ $item->quantity }}</span>
                        <span class="text-white">${{ number_format((float) $item->line_total, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        @if ($quote->demo_links && count($quote->demo_links))
            <div class="mt-6">
                <p class="text-sm font-semibold text-white">See it in action</p>
                <ul class="mt-2 space-y-2">
                    @foreach ($quote->demo_links as $link)
                        <li>
                            <a href="{{ $link }}" target="_blank" rel="noopener" class="text-blue-300 hover:text-blue-200 underline">{{ $link }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (! in_array($quote->status, ['accepted', 'declined', 'invoiced'], true))
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <form method="post" action="{{ route('quote.portal.accept', $quote->portal_token) }}" class="flex-1 space-y-3">
                    @csrf
                    <label class="flex items-start gap-2 text-sm text-slate-300">
                        <input type="checkbox" name="agreed" value="1" required class="mt-1 rounded border-white/30 bg-white/10">
                        <span>I agree to the terms and conditions of this quote.</span>
                    </label>
                    <button type="submit" class="w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow hover:bg-emerald-500">Accept quote</button>
                </form>
                <form method="post" action="{{ route('quote.portal.decline', $quote->portal_token) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full rounded-xl border border-white/20 px-4 py-3 text-sm font-semibold text-white hover:bg-white/10">Decline</button>
                </form>
            </div>
            <p class="mt-3 text-xs text-slate-400">Accepting records your agreement in our system. We will follow up for signature and payment as needed.</p>
        @else
            <p class="mt-6 text-sm text-slate-300">This quote is <strong class="text-white">{{ $quote->status }}</strong>.</p>
        @endif
    </div>
    <p class="mt-6 text-center text-xs text-slate-500">
        <a href="/" class="hover:text-slate-300 transition-colors">← Back to homepage</a>
    </p>
@endsection
