<?php

namespace App\Services;

use App\Enums\DealPriority;
use App\Models\Deal;

class DealScoringService
{
    /**
     * Implements FR-PPL-003 (weighted factors, 0–100 score).
     */
    public function score(Deal $deal): int
    {
        $value = (float) $deal->value;
        $valuePts = match (true) {
            $value <= 100 => 1,
            $value <= 300 => 3,
            $value <= 600 => 5,
            default => 10,
        };

        $prob = (int) ($deal->probability_percent ?? 25);
        $probPts = match ($prob) {
            25 => 2,
            50 => 5,
            75 => 8,
            90 => 10,
            default => (int) round(max(0, min(100, $prob)) / 10),
        };

        $urgencyPts = 1;
        if ($deal->expected_close_date) {
            $days = now()->startOfDay()->diffInDays($deal->expected_close_date, false);
            if ($days >= 0 && $days <= 7) {
                $urgencyPts = 10;
            } elseif ($days <= 14) {
                $urgencyPts = 7;
            } elseif ($days <= 30) {
                $urgencyPts = 4;
            }
        }

        $engagementPts = min(10, (int) ($deal->engagement_points ?? 0));

        $customerPts = 0;
        if ($deal->relationLoaded('client') || $deal->client_id) {
            $client = $deal->client ?? $deal->client()->first();
            if ($client) {
                if ($client->deals()->where('stage', 'won')->where('id', '!=', $deal->id)->exists()) {
                    $customerPts += 5;
                }
                if (($client->source ?? '') === 'referral') {
                    $customerPts += 3;
                }
                if ((float) ($client->lifetime_value ?? 0) >= 5000) {
                    $customerPts += 5;
                }
            }
        }

        $raw = ($valuePts * 3) + ($probPts * 2.5) + ($urgencyPts * 2.5) + ($engagementPts * 1) + ($customerPts * 1);

        return (int) max(0, min(100, round($raw)));
    }

    public function bandFromScore(int $score, Deal $deal): DealPriority
    {
        $inactiveDays = $deal->updated_at ? $deal->updated_at->diffInDays(now()) : 0;
        if ($score < 20 && $inactiveDays > 30 && ! in_array($deal->stage?->value ?? $deal->stage, ['won', 'lost'], true)) {
            return DealPriority::Dead;
        }

        return match (true) {
            $score >= 80 => DealPriority::Hot,
            $score >= 50 => DealPriority::Warm,
            default => DealPriority::Cold,
        };
    }

    public function applyToDeal(Deal $deal): void
    {
        if (in_array($deal->stage?->value ?? $deal->stage, ['won', 'lost'], true)) {
            return;
        }

        $score = $this->score($deal);
        $band = $this->bandFromScore($score, $deal);
        $priorityValue = $band->value;

        if ((int) $deal->priority_score === $score && ($deal->priority instanceof DealPriority ? $deal->priority->value : $deal->priority) === $priorityValue) {
            return;
        }

        $deal->forceFill([
            'priority_score' => $score,
            'priority' => $priorityValue,
        ])->saveQuietly();
    }
}
