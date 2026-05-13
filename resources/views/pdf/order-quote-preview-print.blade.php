<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Quote preview') }} — {{ $order->order_number }}</title>
    <style>
        body { font-family: system-ui, sans-serif; font-size: 14px; color: #111; max-width: 720px; margin: 24px auto; padding: 0 16px; }
        h1 { font-size: 20px; margin: 0 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; }
        .muted { color: #6b7280; font-size: 13px; }
        .right { text-align: right; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 20px; align-items: center; }
        .toolbar a, .toolbar button {
            font: inherit; padding: 8px 14px; border-radius: 6px; border: 1px solid #d1d5db;
            background: #fff; cursor: pointer; text-decoration: none; color: #111;
        }
        .toolbar a.primary { background: #2563eb; border-color: #2563eb; color: #fff; }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; max-width: none; }
        }
    </style>
</head>
<body>
<div class="toolbar no-print">
    <button type="button" onclick="window.print()">{{ __('Print') }}</button>
    <a class="primary" href="{{ route('documents.order.quote-preview.pdf', $order) }}">{{ __('Download PDF') }}</a>
    <a href="{{ route('documents.order.quote-preview.csv', $order) }}">{{ __('Download CSV') }}</a>
    <a href="{{ route('documents.order.quote-preview.xlsx', $order) }}">{{ __('Download Excel') }}</a>
</div>

@include('pdf.partials.order-quote-preview-body')

@if($autoprint ?? false)
    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 200);
        });
    </script>
@endif
</body>
</html>
