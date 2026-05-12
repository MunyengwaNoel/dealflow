@php
    /** @var \App\Models\Deal $record */
    $profit = $record->profit_margin_percent;
    $urgent = $record->stage === \App\Enums\DealStage::Quoted && ! $record->quote_was_opened && $record->updated_at?->lt(now()->subDays(3));
    $border = $urgent ? 'border-red-300 ring-1 ring-red-100' : ($record->quote_was_opened ? 'border-emerald-200' : 'border-amber-200');
@endphp
<div
    id="{{ $record->getKey() }}"
    wire:click="recordClicked('{{ $record->getKey() }}', {{ @json_encode($record) }})"
    class="record cursor-grab rounded-lg border bg-white px-3 py-2 shadow-sm dark:bg-gray-900 {{ $border }}"
    @if($record->timestamps && now()->diffInSeconds($record->{$record::UPDATED_AT}, true) < 3)
        x-data
        x-init="
            $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
            $el.classList.remove('bg-white', 'dark:bg-gray-900')
            setTimeout(() => {
                $el.classList.remove('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
                $el.classList.add('bg-white', 'dark:bg-gray-900')
            }, 3000)
        "
    @endif
>
    <div class="font-semibold text-gray-900 dark:text-white">{{ $record->title }}</div>
    <div class="mt-0.5 text-xs text-gray-500">{{ $record->client?->name }}</div>
    <div class="mt-2 flex justify-between text-xs">
        <span class="text-gray-600">Value</span>
        <span class="font-mono font-bold">${{ number_format((float) $record->value, 0) }}</span>
    </div>
    @if($profit !== null)
        <div class="mt-1 flex justify-between text-xs">
            <span class="text-gray-600">Margin</span>
            <span class="font-mono text-emerald-700">{{ number_format((float) $profit, 1) }}%</span>
        </div>
    @endif
</div>
