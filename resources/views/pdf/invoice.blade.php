<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
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
    <h1>Invoice {{ $invoice->invoice_number }}</h1>
    <p class="muted">Client: {{ $invoice->client?->name }}</p>
    <p class="muted">Issue {{ optional($invoice->issue_date)->toDateString() }} · Due {{ optional($invoice->due_date)->toDateString() }} · Status {{ $invoice->status }}</p>
    @if($invoice->tenant)
        <p class="muted">{{ $invoice->tenant->name }}</p>
    @endif

    <p><strong>Total:</strong> {{ number_format((float) $invoice->total, 2) }}</p>
    <p><strong>Amount paid:</strong> {{ number_format((float) $invoice->amount_paid, 2) }}</p>
    <p><strong>Amount due:</strong> {{ number_format((float) $invoice->amount_due, 2) }}</p>

    @if($invoice->payments->isNotEmpty())
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Method</th>
                <th class="right">Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoice->payments as $p)
                <tr>
                    <td>{{ optional($p->payment_date)->toDateString() }}</td>
                    <td>{{ $p->payment_method }}</td>
                    <td class="right">{{ number_format((float) $p->amount, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @if($invoice->notes)
        <p class="muted" style="margin-top:12px;">{{ $invoice->notes }}</p>
    @endif
</body>
</html>
