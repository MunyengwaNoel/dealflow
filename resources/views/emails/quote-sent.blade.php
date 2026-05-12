<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your quote is ready</title>
</head>
<body style="font-family: system-ui, sans-serif; line-height: 1.5; color: #111;">
    <p>Hi {{ $quote->client?->name ?? 'there' }},</p>
    <p>We have prepared your quote <strong>{{ $quote->quote_number }}</strong> for a total of <strong>${{ number_format((float) $quote->total, 2) }}</strong>.</p>
    <p style="margin: 24px 0;">
        <a href="{{ $portalUrl }}" style="display:inline-block;background:#2563EB;color:#fff;padding:12px 20px;border-radius:10px;text-decoration:none;font-weight:700;">View quote online</a>
    </p>
    <p>A PDF copy is attached for your records.</p>
    <p style="color:#6b7280;font-size:13px;">{{ config('app.name') }}</p>
</body>
</html>
