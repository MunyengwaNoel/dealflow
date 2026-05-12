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
        .qr { margin-top: 16px; }
        .qr img { width: 120px; height: 120px; }
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
    @if((float)($quote->tax_amount ?? 0) > 0)
        <p><strong>Tax:</strong> {{ number_format((float) $quote->tax_amount, 2) }}</p>
    @endif
    <p><strong>Total:</strong> {{ number_format((float) $quote->total, 2) }}</p>
    @if($quote->payment_terms)
        <p class="muted"><strong>Payment terms:</strong> {{ $quote->payment_terms }}</p>
    @endif
    @if($quote->notes)
        <p class="muted">{{ $quote->notes }}</p>
    @endif

    @php
        $portalUrl = $quote->portalUrl();
        $qrDataUri = null;
        if ($portalUrl) {
            try {
                $qr = \Endroid\QrCode\QrCode::create($portalUrl)->setSize(140)->setMargin(4);
                $qrDataUri = (new \Endroid\QrCode\Writer\PngWriter)->write($qr)->getDataUri();
            } catch (\Throwable $e) {
                $qrDataUri = null;
            }
        }
    @endphp
    @if($portalUrl)
        <div class="qr">
            <p class="muted"><strong>View quote online</strong></p>
            @if($qrDataUri)
                <p><img src="{{ $qrDataUri }}" alt="Quote portal QR"></p>
            @endif
            <p class="muted">{{ $portalUrl }}</p>
        </div>
    @endif
</body>
</html>
