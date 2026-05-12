<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quote {{ $quote->quote_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin: 0 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
        .muted { color: #6b7280; font-size: 11px; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h1>Quote {{ $quote->quote_number }}</h1>
    <p class="muted">Client: {{ $quote->client?->name }} · Valid until {{ optional($quote->valid_until)->toDateString() }}</p>
    @if($quote->tenant)
        <p class="muted">{{ $quote->tenant->name }}</p>
    @endif

    <table>
        <thead>
        <tr>
            <th>Item</th>
            <th class="right">Qty</th>
            <th class="right">Sell</th>
            <th class="right">Line</th>
        </tr>
        </thead>
        <tbody>
        @foreach($quote->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="right">{{ $item->quantity }}</td>
                <td class="right">{{ number_format((float) $item->sell_price, 2) }}</td>
                <td class="right">{{ number_format((float) $item->line_total, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p style="margin-top:16px;"><strong>Subtotal:</strong> {{ number_format((float) $quote->subtotal, 2) }}</p>
    <p><strong>Total:</strong> {{ number_format((float) $quote->total, 2) }}</p>
    @if($quote->notes)
        <p class="muted">{{ $quote->notes }}</p>
    @endif
</body>
</html>
