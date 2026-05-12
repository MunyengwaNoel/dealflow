<?php

namespace App\Models;

use App\Enums\DealPriority;
use App\Enums\DealStage;
use App\Services\DealNumberService;
use App\Services\DealScoringService;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'order_id',
        'deal_number',
        'service_template_id',
        'title',
        'description',
        'stage',
        'priority',
        'priority_score',
        'value',
        'cost_total',
        'profit',
        'profit_margin_percent',
        'probability_percent',
        'source',
        'competitor_name',
        'quote_was_opened',
        'demo_was_viewed',
        'engagement_points',
        'expected_close_date',
        'actual_close_date',
        'lost_reason',
        'notes',
        'assigned_to',
    ];

    protected $casts = [
        'expected_close_date' => 'date',
        'actual_close_date' => 'date',
        'quote_opened_at' => 'datetime',
        'quote_accepted_at' => 'datetime',
        'value' => 'decimal:2',
        'cost_total' => 'decimal:2',
        'profit' => 'decimal:2',
        'profit_margin_percent' => 'decimal:2',
        'stage' => DealStage::class,
        'priority' => DealPriority::class,
        'quote_was_opened' => 'boolean',
        'demo_was_viewed' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Deal $deal): void {
            if (! empty($deal->deal_number)) {
                return;
            }
            $tid = $deal->tenant_id ?? (app()->bound('tenant') && app('tenant') ? app('tenant')->id : null);
            if ($tid) {
                $deal->deal_number = app(DealNumberService::class)->next((int) $tid);
            }
        });

        static::saved(function (Deal $deal): void {
            $deal->loadMissing('client');
            app(DealScoringService::class)->applyToDeal($deal);
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function serviceTemplate()
    {
        return $this->belongsTo(ServiceTemplate::class);
    }

    public function activities()
    {
        return $this->hasMany(DealActivity::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
