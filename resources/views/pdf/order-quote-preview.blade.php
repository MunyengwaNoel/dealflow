<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('Quote preview') }} — {{ $order->order_number }}</title>
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
@include('pdf.partials.order-quote-preview-body')
</body>
</html>
