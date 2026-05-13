<h1>{{ __('Quote preview (draft)') }}</h1>
<p class="muted">{{ __('Order') }} {{ $order->order_number }} · {{ __('not yet sent') }}</p>
<p class="muted">{{ __('Client') }}: {{ $client?->name ?? '—' }}</p>
@if($tenant)
    <p class="muted">{{ $tenant->name }}</p>
@endif

<table>
    <thead>
    <tr>
        <th>{{ __('Item') }}</th>
        <th class="right">{{ __('Qty') }}</th>
        <th class="right">{{ __('Sell') }}</th>
        <th class="right">{{ __('Line') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lines as $line)
        <tr>
            <td>{{ $line['name'] }}</td>
            <td class="right">{{ number_format((float) $line['quantity'], 2) }}</td>
            <td class="right">{{ number_format((float) $line['unit_price'], 2) }}</td>
            <td class="right">{{ number_format((float) $line['line_total'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p style="margin-top:16px;"><strong>{{ __('Subtotal') }}:</strong> {{ number_format((float) $subtotal, 2) }}</p>
<p class="muted"><strong>{{ __('Internal cost') }}:</strong> {{ number_format((float) $total_cost, 2) }}</p>
<p class="muted"><strong>{{ __('Profit') }}:</strong> {{ number_format((float) $profit, 2) }}@if($margin_percent !== null) ({{ number_format((float) $margin_percent, 2) }}%)@endif</p>

@if($payment_terms)
    <p class="muted" style="margin-top:12px;"><strong>{{ __('Payment terms') }}:</strong> {{ $payment_terms }}</p>
@endif
@if($personal_message)
    <p class="muted" style="margin-top:8px;"><strong>{{ __('Message') }}:</strong> {{ $personal_message }}</p>
@endif
