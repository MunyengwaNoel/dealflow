<?php

namespace App\Http\Controllers;

use App\Enums\DealStage;
use App\Models\Quote;
use App\Models\QuoteAnalytic;
use App\Services\DealScoringService;
use App\Tenancy\TenantScope;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicQuoteController extends Controller
{
    public function show(string $token): View
    {
        $quote = Quote::query()
            ->withoutGlobalScope(TenantScope::class)
            ->where('portal_token', $token)
            ->with(['items', 'client', 'tenant', 'deal'])
            ->firstOrFail();

        if ($quote->status === 'sent') {
            $quote->forceFill([
                'status' => 'viewed',
                'viewed_at' => now(),
            ])->saveQuietly();
        }

        QuoteAnalytic::query()->create([
            'quote_id' => $quote->id,
            'event_type' => 'opened',
            'event_data' => null,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 2000),
        ]);

        if ($quote->deal_id) {
            $deal = $quote->deal;
            $deal?->forceFill([
                'quote_was_opened' => true,
                'quote_opened_at' => $deal->quote_opened_at ?? now(),
                'engagement_points' => min(10, (int) ($deal->engagement_points ?? 0) + 2),
            ])->saveQuietly();
            app(DealScoringService::class)->applyToDeal($deal->fresh());
        }

        return view('quote-portal.show', ['quote' => $quote]);
    }

    public function accept(Request $request, string $token)
    {
        $quote = Quote::query()->withoutGlobalScope(TenantScope::class)->where('portal_token', $token)->firstOrFail();

        $request->validate([
            'agreed' => 'accepted',
        ]);

        $quote->forceFill([
            'status' => 'accepted',
            'accepted_at' => now(),
        ])->saveQuietly();

        QuoteAnalytic::query()->create([
            'quote_id' => $quote->id,
            'event_type' => 'accepted',
            'event_data' => null,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 2000),
        ]);

        if ($quote->deal_id) {
            $quote->deal?->forceFill([
                'stage' => DealStage::Won,
                'actual_close_date' => now()->toDateString(),
                'quote_accepted_at' => now(),
            ])->saveQuietly();
            app(DealScoringService::class)->applyToDeal($quote->deal->fresh());
        }

        return redirect()->route('quote.portal', ['token' => $token])
            ->with('status', 'Thank you. Your acceptance has been recorded. Our team will contact you shortly.');
    }

    public function decline(Request $request, string $token)
    {
        $quote = Quote::query()->withoutGlobalScope(TenantScope::class)->where('portal_token', $token)->firstOrFail();

        $data = $request->validate([
            'decline_reason' => 'nullable|string|max:2000',
        ]);

        $quote->forceFill([
            'status' => 'declined',
            'declined_at' => now(),
            'decline_reason' => $data['decline_reason'] ?? null,
        ])->saveQuietly();

        QuoteAnalytic::query()->create([
            'quote_id' => $quote->id,
            'event_type' => 'declined',
            'event_data' => $data,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 2000),
        ]);

        if ($quote->deal_id) {
            $quote->deal?->forceFill([
                'stage' => DealStage::Lost,
                'lost_reason' => $data['decline_reason'] ?? 'Quote declined',
                'actual_close_date' => now()->toDateString(),
            ])->saveQuietly();
        }

        return redirect()->route('quote.portal', ['token' => $token])
            ->with('status', 'Your response has been recorded.');
    }

    public function demoViewed(string $token)
    {
        $quote = Quote::query()->withoutGlobalScope(TenantScope::class)->where('portal_token', $token)->firstOrFail();

        QuoteAnalytic::query()->create([
            'quote_id' => $quote->id,
            'event_type' => 'demo_viewed',
            'event_data' => request()->only(['label', 'url']),
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 2000),
        ]);

        if ($quote->deal_id) {
            $quote->deal?->forceFill([
                'demo_was_viewed' => true,
                'engagement_points' => min(10, (int) ($quote->deal->engagement_points ?? 0) + 2),
            ])->saveQuietly();
            app(DealScoringService::class)->applyToDeal($quote->deal->fresh());
        }

        return response()->json(['ok' => true]);
    }
}
