<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between">
                <span>Alerts</span>
                @if($expiringDocs->count() + $overdueInvoices->count() > 0)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-50 text-red-600 text-xs font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        {{ $expiringDocs->count() + $overdueInvoices->count() }} active
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold">
                        All clear
                    </span>
                @endif
            </div>
        </x-slot>

        <div class="flex flex-col gap-3">

            {{-- Expiring documents --}}
            @forelse($expiringDocs as $doc)
                @php
                    $expiryDay = \Illuminate\Support\Carbon::parse($doc->expiry_date)->startOfDay();
                    $daysLeft = (int) now()->startOfDay()->diffInDays($expiryDay, false);
                    $urgent   = $daysLeft <= 7;
                @endphp
                <div class="flex items-start gap-3 p-3 rounded-xl {{ $urgent ? 'bg-red-50 border border-red-100' : 'bg-amber-50 border border-amber-100' }}">
                    <div class="w-8 h-8 rounded-lg {{ $urgent ? 'bg-red-100' : 'bg-amber-100' }} flex items-center justify-center shrink-0">
                        <x-heroicon-m-document-minus class="w-4 h-4 {{ $urgent ? 'text-red-500' : 'text-amber-500' }}" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold {{ $urgent ? 'text-red-800' : 'text-amber-800' }} truncate">
                            {{ $doc->document_type ?? $doc->name ?? 'Document' }} expiring
                        </p>
                        <p class="text-xs {{ $urgent ? 'text-red-500' : 'text-amber-500' }} mt-0.5">
                            {{ $doc->client?->name ?? 'Unknown client' }}
                            &middot;
                            {{ $daysLeft === 0 ? 'Today' : ($daysLeft === 1 ? 'Tomorrow' : "in {$daysLeft} days") }}
                        </p>
                    </div>
                </div>
            @empty
            @endforelse

            {{-- Overdue invoices --}}
            @forelse($overdueInvoices as $inv)
                <div class="flex items-start gap-3 p-3 rounded-xl bg-orange-50 border border-orange-100">
                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center shrink-0">
                        <x-heroicon-m-banknotes class="w-4 h-4 text-orange-500" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-orange-800 truncate">
                            Invoice overdue
                        </p>
                        <p class="text-xs text-orange-500 mt-0.5">
                            {{ $inv->client?->name ?? 'Unknown' }}
                            &middot;
                            ${{ number_format($inv->amount_due, 2) }} due
                        </p>
                    </div>
                </div>
            @empty
            @endforelse

            {{-- All clear --}}
            @if($expiringDocs->isEmpty() && $overdueInvoices->isEmpty())
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center mb-3">
                        <x-heroicon-m-check-circle class="w-5 h-5 text-emerald-500" />
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Nothing needs attention</p>
                    <p class="text-xs text-slate-400 mt-1">All documents and invoices are in order.</p>
                </div>
            @endif

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
