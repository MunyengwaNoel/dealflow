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
