<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Services\OrderNumberService;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'client_id',
        'order_number',
        'status',
        'wizard_state',
        'total_amount',
        'total_cost',
        'profit_amount',
        'profit_margin',
        'payment_terms',
        'deposit_amount',
        'balance_amount',
        'notes',
        'created_by',
        'quote_id',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'wizard_state' => 'array',
        'total_amount' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'profit_amount' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
        'status' => OrderStatus::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if (! empty($order->order_number)) {
                return;
            }
            $tid = $order->tenant_id ?? (app()->bound('tenant') && app('tenant') ? app('tenant')->id : null);
            if ($tid) {
                $order->order_number = app(OrderNumberService::class)->next((int) $tid);
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function getServicesSummaryAttribute(): string
    {
        $keys = $this->wizard_state['selected_services'] ?? [];
        if (! is_array($keys) || $keys === []) {
            return '—';
        }
        $labels = [
            'website' => 'Website',
            'email' => 'Email',
            'company_reg' => 'Company reg.',
            'domain' => 'Domain',
            'tax_clearance' => 'Tax clearance',
            'business_plan' => 'Business plan',
            'paid_social' => 'Paid social ads',
        ];

        return collect($keys)->map(fn (string $k) => $labels[$k] ?? $k)->implode(' + ');
    }
}
